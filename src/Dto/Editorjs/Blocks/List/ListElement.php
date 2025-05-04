<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;
use Patrikjak\Starter\Enums\Editorjs\ListStyle;

class ListElement implements Block
{
    /**
     * @param array<ListItem> $items
     */
    public function __construct(public string $id, public ListStyle $style, public array $items = [])
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => BlockType::List->value,
            'data' => [
                'style' => $this->style->value,
                'items' => array_map(fn (ListItem $item) => [
                    'content' => $item->content,
                    'meta' => $item->itemMeta->toArray(),
                    'items' => $this->getItemsArray($this->items),
                ], $this->items),
            ],
        ];
    }

    private function getItemsArray($listItems): array
    {
        $toReturn = [];

        foreach ($listItems as $item) {
            $toReturn[] = [
                'content' => $item->content,
                'meta' => $item->itemMeta->toArray(),
                'items' => $this->getItemsArray($item->items),
            ];
        }

        return $toReturn;
    }
}