<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Header;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Header implements Block
{
    public function __construct(public string $id, public string $text, public int $level)
    {
    }

    public function getType(): BlockType
    {
        return BlockType::Header;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->getType()->value,
            'data' => [
                'text' => $this->text,
                'level' => $this->level,
            ],
        ];
    }
}