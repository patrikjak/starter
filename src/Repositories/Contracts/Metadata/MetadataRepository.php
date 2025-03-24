<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Metadata;

use Patrikjak\Starter\Dto\Metadata\CreateMetadata;
use Patrikjak\Starter\Dto\Metadata\UpdateMetadata;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface MetadataRepository extends SupportsPagination
{
    public function create(CreateMetadata $createMetadata): void;

    public function update(string $id, UpdateMetadata $updateMetadata): void;

    public function delete(string $id): void;
}