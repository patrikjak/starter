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

    /**
     * The delete action is not bypassed for superadmins — default roles and the last
     * superadmin role must remain undeletable regardless of who is acting.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($ability === self::DELETE) {
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

        if ($model->is_superadmin) {
            $roleRepository = app(RoleRepository::class);
            assert($roleRepository instanceof RoleRepository);

            if ($roleRepository->countSuperadminRoles() <= 1) {
                return false;
            }
        }

        return $this->hasPermission($user, self::DELETE)
            && $user->role->id !== $model->id;
    }

    public function manageProtected(User $user): bool
    {
        return $this->hasPermission($user, self::MANAGE_PROTECTED);
    }
}
