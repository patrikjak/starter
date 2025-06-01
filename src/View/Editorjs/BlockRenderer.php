<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;

abstract class BlockRenderer
{
    abstract public function render(): string;

    public function __construct(protected Block $block)
    {
    }
}