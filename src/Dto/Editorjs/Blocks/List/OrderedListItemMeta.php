<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks\List;

use Patrikjak\Starter\Enums\Editorjs\CounterType;

class OrderedListItemMeta implements ItemMeta
{
    public function __construct(public int $start = 1, public CounterType $counterType = CounterType::Numeric)
    {
    }

    public function toArray(): array
    {
        return [
            'start' => $this->start,
            'counterType' => $this->counterType->value,
        ];
    }
}