<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Paragraph implements Block
{
    public function __construct(public string $id, public string $text)
    {
    }

    public function getType(): BlockType
    {
        return BlockType::Paragraph;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => BlockType::Paragraph->value,
            'data' => ['text' => $this->text],
        ];
    }
}