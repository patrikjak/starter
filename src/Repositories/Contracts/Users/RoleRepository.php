<?php

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Auth\Models\Role;
use Patrikjak\Auth\Repositories\Interfaces\RoleRepository as BaseRoleRepository;

interface RoleRepository extends BaseRoleRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function getAllWithoutSuperAdminPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function attachPermissions(Role $role, array $permissions): void;
}