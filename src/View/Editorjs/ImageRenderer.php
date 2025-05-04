<?php

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Image\Image;

class ImageRenderer extends BlockRenderer
{
    public function render(): string
    {
        assert($this->block instanceof Image);

        $url = $this->block->url;
        $alt = basename($url);

        $class = $this->getClassAttribute();
        $classAttribute = $class === '' ? '' : sprintf(' class="%s"', $class);

        return sprintf('<img src="%s" alt="%s"%s>', $url, $alt, $classAttribute);
    }

    private function getClassAttribute(): string
    {
        assert($this->block instanceof Image);

        $class = [];

        if ($this->block->withBorder) {
            $class[] = 'with-border';
        }

        if ($this->block->withBackground) {
            $class[] = 'with-background';
        }

        if ($this->block->stretched) {
            $class[] = 'stretched';
        }

        if (count($class) === 0) {
            return '';
        }

        return implode(' ', $class);
    }
}