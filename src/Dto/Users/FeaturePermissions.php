<?php

namespace Patrikjak\Starter\Dto\Users;

class FeaturePermissions
{
    /**
     * @param array<Permission> $permissions
     */
    public function __construct(public string $feature, public array $permissions)
    {
    }
}