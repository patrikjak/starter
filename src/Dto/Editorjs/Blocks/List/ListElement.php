<?php

declare(strict_types = 1);

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

    public function getType(): BlockType
    {
        return BlockType::List;
    }
}