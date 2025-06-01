<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

use Patrikjak\Starter\Enums\Editorjs\CounterType;

class OrderedListItemMeta implements ItemMeta
{
    public function __construct(public int $start = 1, public CounterType $counterType = CounterType::Numeric)
    {
    }
}