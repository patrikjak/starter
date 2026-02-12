<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;

trait EloquentSupportsPagination
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
