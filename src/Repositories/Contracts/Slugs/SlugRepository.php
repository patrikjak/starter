<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Slugs;

use Patrikjak\Starter\Dto\Slugs\CreateSlug;
use Patrikjak\Starter\Dto\Slugs\UpdateSlug;
use Patrikjak\Starter\Models\Slugs\Slug;

interface SlugRepository
{
    public function existsSameSlug(string $slug, ?string $prefix = null, ?string $ignoredId = null): ?Slug;

    public function getByUri(string $uri): ?Slug;

    public function getBySlug(string $slug, ?string $prefix = null): ?Slug;

    public function create(CreateSlug $createSlug): void;

    public function update(string $id, UpdateSlug $updateSlug): void;

    public function delete(string $id): void;
}