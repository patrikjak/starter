<?php

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;

abstract class BlockRenderer
{
    public function __construct(protected Block $block)
    {
    }

    abstract public function render(): string;
}