<?php

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ChecklistItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListElement;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListItem;
use Patrikjak\Starter\Enums\Editorjs\ListStyle;

class ListRenderer extends BlockRenderer
{
    public function render(): string
    {
        assert($this->block instanceof ListElement);

        $style = $this->block->style;
        $tag = $this->getTag($style);

        return sprintf(
            '<%s class="%s">%s</%s>',
            $tag,
            $this->block->style->value,
            $this->renderItems($this->block->items, $tag),
            $tag,
        );
    }

    private function getTag(ListStyle $style): string
    {
        return match ($style) {
            ListStyle::Ordered => 'ol',
            ListStyle::Unordered, ListStyle::Checklist => 'ul',
        };
    }

    private function renderItems(array $listItems, string $tag): string
    {
        assert($this->block instanceof ListElement);

        $items = [];

        foreach ($listItems as $item) {
            $items[] = $this->renderItem($item);

            if (count($item->items) > 0) {
                $items[] = sprintf(
                    '<%s class="nested %s">%s</%s>',
                    $tag,
                    $this->block->style->value,
                    $this->renderItems($item->items, $tag),
                    $tag,
                );
            }
        }

        return implode(PHP_EOL, $items);
    }

    private function renderItem(ListItem $item): string
    {
        assert($this->block instanceof ListElement);

        if ($this->block->style === ListStyle::Checklist) {
            assert($item->itemMeta instanceof ChecklistItemMeta);

            return sprintf(
                '<li><input type="checkbox" %s> %s</li>',
                $item->itemMeta->checked ? 'checked' : '',
                $item->content,
            );
        }

        return sprintf(
            '<li>%s</li>',
            $item->content,
        );
    }
}