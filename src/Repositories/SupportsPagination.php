<?php

namespace Patrikjak\Starter\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

trait SupportsPagination
{
    public function getAllPaginatedByModel(
        string $model,
        int $pageSize,
        int $page,
        string $refreshUrl,
    ): LengthAwarePaginator {
        return $model::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }
}