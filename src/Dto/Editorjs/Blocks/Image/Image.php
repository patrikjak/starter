<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Image;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Image implements Block
{
    public function __construct(
        public string $id,
        public string $type,
        public string $url,
        public ?string $caption = null,
        public bool $withBorder = false,
        public bool $withBackground = false,
        public bool $stretched = false,
    ) {
    }

    public function getType(): BlockType
    {
        return BlockType::Image;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->getType()->value,
            'data' => [
                'url' => $this->url,
                'caption' => $this->caption,
                'withBorder' => $this->withBorder,
                'withBackground' => $this->withBackground,
                'stretched' => $this->stretched,
            ],
        ];
    }
}