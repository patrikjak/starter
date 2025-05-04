<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

class ListItem
{
    /**
     * @param array<ListItem> $items
     */
    public function __construct(public string $content, public ItemMeta $itemMeta, public array $items = [])
    {
    }
}