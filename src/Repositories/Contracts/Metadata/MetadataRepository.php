<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Metadata;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Metadata\CreateMetadata;
use Patrikjak\Starter\Dto\Metadata\UpdateMetadata;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;

interface MetadataRepository
{
    public function getAllPaginated(
        int $pageSize,
        int $page,
        string $refreshUrl,
        ?SortCriteria $sortCriteria,
        ?FilterCriteria $filterCriteria,
    ): LengthAwarePaginator;

    public function create(CreateMetadata $createMetadata): void;

    public function update(string $id, UpdateMetadata $updateMetadata): void;

    public function delete(string $id): void;
}