<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

class ChecklistItemMeta implements ItemMeta
{
    public function __construct(public bool $checked = false)
    {
    }
}