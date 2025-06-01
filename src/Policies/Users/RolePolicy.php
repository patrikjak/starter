<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Users;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'role';

    public const string VIEW_SUPERADMIN = 'viewSuperadmin';

    public const string MANAGE = 'manage';

    public const string MANAGE_PROTECTED = 'manageProtected';

    public function viewSuperAdmin(User $user): bool
    {
        return $this->hasPermission($user, self::VIEW_SUPERADMIN);
    }

    public function view(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        return parent::view($user, $model) && $model->id !== RoleType::SUPERADMIN->value;
    }

    public function manage(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        return $this->hasPermission($user, self::MANAGE) && $model->id !== RoleType::SUPERADMIN->value
            && $user->role->id !== $model->id;
    }

    public function manageProtected(User $user): bool
    {
        return $this->hasPermission($user, self::MANAGE_PROTECTED);
    }
}
