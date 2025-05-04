<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Raw\Raw;

class RawRenderer extends BlockRenderer
{
    public function render(): string
    {
        assert($this->block instanceof Raw);

        return $this->block->html;
    }
}