<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface SupportsPagination
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;
}