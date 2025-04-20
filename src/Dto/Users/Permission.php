<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Users;

class Permission
{
    public function __construct(
        public string $action,
        public array $descriptions,
        public bool $protected = false,
        public array $defaultRoles = [],
    ) {
    }
}