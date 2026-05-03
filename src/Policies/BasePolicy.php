<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Policies;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Models\Users\User;

class BasePolicy
{
    protected const string FEATURE_NAME = '';

    public const string VIEW_ANY = 'viewAny';

    public const string VIEW = 'view';

    public const string CREATE = 'create';

    public const string EDIT = 'edit';

    public const string DELETE = 'delete';

    /**
     * Superadmins bypass all policy checks unconditionally, including checks that protect
     * a user from modifying their own role (e.g. RolePolicy::manage).
     * Subclasses may override this to restrict the bypass for specific abilities.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role->is_superadmin) {
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
