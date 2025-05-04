<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

interface Block extends Arrayable
{
    public function getType(): BlockType;
    
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}