<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\User;

class BasePolicy
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

    public function viewAny(User $user, ?Model $model = null): bool
    {
        return $this->hasPermission($user, self::VIEW_ANY);
    }

    public function view(User $user, ?Model $model = null): bool
    {
        return $this->hasPermission($user, self::VIEW);
    }

    public function create(User $user, ?Model $model = null): bool
    {
        return $this->hasPermission($user, self::CREATE);
    }

    public function edit(User $user, ?Model $model = null): bool
    {
        return $this->hasPermission($user, self::EDIT);
    }

    public function delete(User $user, ?Model $model = null): bool
    {
        return $this->hasPermission($user, self::DELETE);
    }

    public function hasPermission(User $user, string $action): bool
    {
        return $user->hasPermission(static::FEATURE_NAME, $action);
    }
}