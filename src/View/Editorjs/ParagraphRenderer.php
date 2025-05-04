<?php

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph\Paragraph;

class ParagraphRenderer extends BlockRenderer
{
    public function render(): string
    {
        assert($this->block instanceof Paragraph);

        $text = $this->block->text;

        return sprintf('<p>%s</p>', $text);
    }
}