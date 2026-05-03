<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Policies\Users;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'role';

    private const string PROTECTED_SLUG = 'superadmin';

    public const string VIEW_SUPERADMIN = 'viewSuperadmin';

    public const string MANAGE = 'manage';

    public const string MANAGE_PROTECTED = 'manageProtected';

    public function __construct(private readonly RoleRepository $roleRepository)
    {
    }

    /**
     * The delete and manage actions are not bypassed for superadmins — the last superadmin
     * role must remain undeletable, and a user must never manage permissions on their own role.
     */
    public function before(User $user, string $ability): ?bool
    {
        if (in_array($ability, [self::DELETE, self::MANAGE], true)) {
            return null;
        }

        return parent::before($user, $ability);
    }

    public function viewSuperAdmin(User $user): bool
    {
        return $this->hasPermission($user, self::VIEW_SUPERADMIN);
    }

    public function view(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        return parent::view($user, $model) && !$model->is_superadmin;
    }

    public function edit(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        return $this->hasPermission($user, self::EDIT) && !$model->is_superadmin;
    }

    public function manage(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        return $this->hasPermission($user, self::MANAGE)
            && !$model->is_superadmin
            && $user->role->id !== $model->id;
    }

    public function delete(User $user, ?Model $model = null): bool
    {
        assert($model instanceof Role);

        if ($model->slug === self::PROTECTED_SLUG) {
            return false;
        }

        if ($model->is_superadmin && $this->roleRepository->countSuperadminRoles() <= 1) {
            return false;
        }

        return $this->hasPermission($user, self::DELETE)
            && $user->role->id !== $model->id;
    }

    public function manageProtected(User $user): bool
    {
        return $this->hasPermission($user, self::MANAGE_PROTECTED);
    }
}
