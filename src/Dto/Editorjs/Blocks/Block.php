<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks;

use Patrikjak\Starter\Enums\Editorjs\BlockType;

interface Block
{
    public function getType(): BlockType;
}