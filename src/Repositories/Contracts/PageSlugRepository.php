<?php

namespace Patrikjak\Starter\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreatePageSlug;

interface PageSlugRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function create(CreatePageSlug $createPageSlug): void;

    public function update(CreatePageSlug $createPageSlug, string $id): void;

    public function delete(string $id): void;
}