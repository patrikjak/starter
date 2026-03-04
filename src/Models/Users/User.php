<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Auth\Models\User as BaseUser;

/**
 * @property Role $role
 * @property string $role_name
 */
class User extends BaseUser
{
    use AvailablePermissions;

    public function hasPermission(string $feature, string $action): bool
    {
        return $this->role->permissions()->where('name', sprintf('%s-%s', $action, $feature))->exists();
    }
}
