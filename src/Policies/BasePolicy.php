<?php

namespace Patrikjak\Starter\Policies;

use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\User;

abstract class BasePolicy
{
    public const string VIEW_ANY = 'viewAny';

    public const string VIEW = 'view';

    public const string CREATE = 'create';

    public const string EDIT = 'edit';

    public const string DELETE = 'delete';

    public function before(User $user): ?bool
    {
        if ($user->hasRole(RoleType::SUPERADMIN)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, self::VIEW_ANY);
    }

    public function view(User $user): bool
    {
        return $this->hasPermission($user, self::VIEW);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, self::CREATE);
    }

    public function edit(User $user): bool
    {
        return $this->hasPermission($user, self::EDIT);
    }

    public function hasPermission(User $user, string $action): bool
    {
        return $user->hasPermission(static::FEATURE_NAME, $action);
    }
}