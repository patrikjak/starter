<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Repositories\Interfaces\RoleRepository as BaseRoleRepository;
use Patrikjak\Starter\Models\Users\Role;

interface RoleRepository extends BaseRoleRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function getAllWithoutSuperAdminPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function attachPermissions(Role $role, array $permissions): void;

    /**
     * @param array<int> $permissions
     */
    public function syncPermissions(Role $role, array $permissions): void;

    public function getRolePermissions(Role $role): Collection;
}