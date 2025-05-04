<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Raw;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Raw implements Block
{
    public function __construct(public string $id, public string $html)
    {
    }

    public function getType(): BlockType
    {
        return BlockType::Raw;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => BlockType::Raw->value,
            'data' => [
                'html' => $this->html,
            ],
        ];
    }
}