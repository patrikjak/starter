<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Dto\Common;

final readonly class NavigationGroup
{
    /**
     * @param array<NavigationItem> $items
     */
    public function __construct(
        public string $label,
        public array $items,
    ) {
    }
}
