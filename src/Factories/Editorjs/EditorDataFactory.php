<?php

namespace Patrikjak\Starter\Factories\Editorjs;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Header;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

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
        };
    }

    private static function mapParagraph(array $blockData): Paragraph
    {
        return new Paragraph($blockData['data']['text'] ?? '');
    }

    private static function mapHeader(array $blockData): Header
    {
        $data = $blockData['data'];

        return new Header($data['text'], $data['level']);
    }
}