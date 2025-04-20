<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Repositories\RoleRepository as BaseRoleRepository;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository as RoleRepositoryContract;

class RoleRepository extends BaseRoleRepository implements RoleRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Role::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    public function getAllWithoutSuperAdminPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Role::where('name', '!=', RoleType::SUPERADMIN->name)
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }

    /**
     * @param array<int> $permissions
     */
    public function attachPermissions(Role $role, array $permissions): void
    {
        $role->permissions()->syncWithoutDetaching($permissions);
    }

    /**
     * @inheritDoc
     */
    public function syncPermissions(Role $role, array $permissions): void
    {
        $role->permissions()->sync($permissions);
    }

    public function getRolePermissions(Role $role): Collection
    {
        return $role->permissions;
    }
}