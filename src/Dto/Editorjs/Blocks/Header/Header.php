<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Header;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Header implements Block
{
    public function __construct(public string $id, public string $text, public int $level)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => BlockType::Header->value,
            'data' => [
                'text' => $this->text,
                'level' => $this->level,
            ],
        ];
    }
}