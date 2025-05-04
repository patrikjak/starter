<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\Image;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;

class Image implements Block
{
    public function __construct(
        public string $id,
        public string $type,
        public string $url,
        public string $caption,
        public string $withBorder,
        public string $withBackground,
        public string $stretched,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
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