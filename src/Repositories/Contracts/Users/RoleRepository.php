<?php

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Auth\Repositories\Interfaces\RoleRepository as BaseRoleRepository;
use Patrikjak\Starter\Models\Users\Role;

interface RoleRepository extends BaseRoleRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function getAllWithoutSuperAdminPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function attachPermissions(Role $role, array $permissions): void;
}