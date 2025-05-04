<?php

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Header\Header;

class HeaderRenderer extends BlockRenderer
{
    public function render(): string
    {
        assert($this->block instanceof Header);

        $level = $this->block->level;
        $text = $this->block->text;

        return sprintf('<h%d>%s</h%d>', $level, $text, $level);
    }
}