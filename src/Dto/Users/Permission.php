<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Dto\Users;

readonly class Permission
{
    /**
     * @param array<string> $descriptions
     * @param array<string> $defaultRoles role slugs
     */
    public function __construct(
        public string $action,
        public array $descriptions,
        public bool $protected = false,
        public array $defaultRoles = [],
    ) {
    }
}
