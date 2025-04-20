<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\UserRepository as UsersRepositoryContract;

class UserRepository implements UsersRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return User::with('role')
            ->select('users.*', 'r.name AS role_name', 'r.id AS role_id')
            ->join('roles AS r', 'users.role_id', '=', 'r.id')
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }

    public function getAllExceptSuperAdminsPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return User::with('role')
            ->select('users.*', 'r.name AS role_name', 'r.id AS role_id')
            ->join('roles AS r', 'users.role_id', '=', 'r.id')
            ->where('r.name', '!=', RoleType::SUPERADMIN->name)
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }
}