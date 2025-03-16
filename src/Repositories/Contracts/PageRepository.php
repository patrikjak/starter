<?php

namespace Patrikjak\Starter\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface PageRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;
}