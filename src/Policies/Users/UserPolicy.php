<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Policies\Users;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'user';

    public const string VIEW_SUPERADMIN = 'viewSuperadmin';

    /**
     * The edit action is not bypassed for superadmins — a user must never be able
     * to change their own role regardless of whether they are a superadmin.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($ability === self::EDIT) {
            return null;
        }

        return parent::before($user, $ability);
    }

    public function edit(User $user, ?Model $model = null): bool
    {
        if (!$model instanceof User) {
            return $this->hasPermission($user, self::EDIT);
        }

        if ($user->id === $model->id) {
            return false;
        }

        if ($model->role->is_superadmin && !$user->canViewSuperAdmin()) {
            return false;
        }

        return $this->hasPermission($user, self::EDIT);
    }
}
