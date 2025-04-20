<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function getAllExceptSuperAdminsPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;
}