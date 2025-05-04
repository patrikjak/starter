<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

class ChecklistItemMeta implements ItemMeta
{
    public function __construct(public bool $checked = false)
    {
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'checked' => $this->checked,
        ];
    }
}