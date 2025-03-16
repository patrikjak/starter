<?php

namespace Patrikjak\Starter\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Models\Page;
use Patrikjak\Starter\Repositories\Contracts\PageRepository as PageRepositoryContract;

class PageRepository implements PageRepositoryContract
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Page::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }
}