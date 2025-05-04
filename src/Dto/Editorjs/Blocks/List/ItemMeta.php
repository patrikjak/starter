<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

use Illuminate\Contracts\Support\Arrayable;

interface ItemMeta extends Arrayable
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}