<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Factories\Editorjs;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Header\Header;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Image\Image;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ChecklistItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListElement;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListItem;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\OrderedListItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\UnorderedListItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph\Paragraph;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Raw\Raw;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Editorjs\BlockType;
use Patrikjak\Starter\Enums\Editorjs\ListStyle;

class EditorDataFactory
{
    public static function createFromOutputData(array $outputData): EditorData
    {
        return new EditorData(
            CarbonImmutable::createFromTimestamp($outputData['time']),
            self::getBlocks($outputData['blocks']),
            $outputData['version'],
            json_encode($outputData),
        );
    }

    private static function getBlocks(array $blocks): Collection
    {
        $mappedBlocks = [];

        foreach ($blocks as $blockData) {
            $mappedBlocks[] = self::getBlock($blockData);
        }

        return new Collection($mappedBlocks);
    }

    private static function getBlock(array $blockData): Block
    {
        $blockType = BlockType::from($blockData['type']);

        return match ($blockType) {
            BlockType::Paragraph => self::mapParagraph($blockData),
            BlockType::Header => self::mapHeader($blockData),
            BlockType::List => self::mapList($blockData),
            BlockType::Raw => self::mapRaw($blockData),
            BlockType::Image => self::mapImage($blockData),
        };
    }

    private static function mapParagraph(array $blockData): Paragraph
    {
        return new Paragraph($blockData['id'], $blockData['data']['text'] ?? '');
    }

    private static function mapHeader(array $blockData): Header
    {
        $data = $blockData['data'];

        return new Header($blockData['id'], $data['text'], $data['level']);
    }

    private static function mapList(array $blockData): ListElement
    {
        $data = $blockData['data'];
        $style = ListStyle::from($data['style']);

        return new ListElement(
            $blockData['id'],
            $style,
            self::mapListItems($data['items'], $style),
        );
    }

    private static function mapRaw(array $blockData): Raw
    {
        return new Raw($blockData['id'], $blockData['data']['html']);
    }

    private static function mapImage(array $blockData): Image
    {
        $data = $blockData['data'];

        return new Image(
            $blockData['id'],
            $blockData['type'],
            $data['file']['url'],
            $data['caption'] ?? '',
            $data['withBorder'] ?? false,
            $data['withBackground'] ?? false,
            $data['stretched'] ?? false,
        );
    }

    /**
     * @return array<ListItem>
     */
    private static function mapListItems(array $items, ListStyle $style): array
    {
        $mappedItems = [];

        foreach ($items as $item) {
            $itemItems = $item['items'] ?? [];
            $mappedItemItems = [];

            if (count($itemItems) > 0) {
                $mappedItemItems = self::mapListItems($itemItems, $style);
            }

            $mappedItems[] = new ListItem(
                $item['content'],
                self::getListMeta($item['meta'], $style),
                $mappedItemItems,
            );
        }

        return $mappedItems;
    }

    private static function getListMeta(array $meta, ListStyle $style): ItemMeta
    {
        return match ($style) {
            ListStyle::Ordered => new OrderedListItemMeta(),
            ListStyle::Unordered => new UnorderedListItemMeta(),
            ListStyle::Checklist => new ChecklistItemMeta($meta['checked'] ?? false),
        };
    }
}