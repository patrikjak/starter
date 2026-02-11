<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;
use Patrikjak\Starter\Support\Traits\HandlesNullableAuthUser;

class PermissionsService
{
    use HandlesNullableAuthUser;

    public function __construct(private readonly PermissionRepository $permissionRepository, AuthManager $authManager)
    {
        $this->initializeUser($authManager);
    }

    /**
     * @return Collection<string, Collection<int, mixed>>
     */
    public function getAllAvailablePermissionsGroupedByFeature(): Collection
    {
        $canViewProtected = $this->getUserPermission(
            static fn (User $user) => $user->canViewProtectedPermissions(),
        );
        $availablePermissions = $canViewProtected
            ? $this->permissionRepository->getAll()
            : $this->permissionRepository->getAllUnprotected();

        return $availablePermissions->groupBy(static function (Permission $permission) {
            return explode('-', $permission->name)[1];
        });
    }

    /**
     * @param array<string> $permissions
     * @return Collection<Permission>
     */
    public function getAvailablePermissionsFromNames(array $permissions): Collection
    {
        $permissions = $this->permissionRepository->getByNames($permissions);
        $canViewProtectedPermissions = $this->getUserPermission(
            static fn (User $user) => $user->canViewProtectedPermissions(),
        );

        $availablePermissions = $permissions->filter(
            static function (Permission $permission) use ($canViewProtectedPermissions) {
                if (!$canViewProtectedPermissions && $permission->protected) {
                    return false;
                }

                return true;
        });

        return new Collection($availablePermissions);
    }
}