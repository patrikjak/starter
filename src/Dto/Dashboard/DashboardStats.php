<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Dto\Dashboard;

final readonly class DashboardStats
{
    public function __construct(
        public ?int $articleCount,
        public ?int $categoryCount,
        public ?int $authorCount,
        public ?int $staticPageCount,
        public bool $hasStats,
    ) {
    }
}
