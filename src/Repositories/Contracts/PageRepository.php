<?php

namespace Patrikjak\Starter\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreatePage;

interface PageRepository
{
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function create(CreatePage $newPage): void;

    public function update(CreatePage $createPage, string $id): void;

    public function delete(string $id): void;
}