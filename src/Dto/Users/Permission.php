<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Users;

use Patrikjak\Auth\Models\RoleType;

class Permission
{
    /**
     * @param array<string> $descriptions
     * @param array<RoleType> $defaultRoles
     */
    public function __construct(
        public string $action,
        public array $descriptions,
        public bool $protected = false,
        public array $defaultRoles = [],
    ) {
    }
}