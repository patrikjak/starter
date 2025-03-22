<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\PageSlugs;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Dto\PageSlugs\UpdatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;

interface PageSlugRepository
{
    public function getBySlug(string $slug): ?PageSlug;

    public function create(CreatePageSlug $createPageSlug): void;

    public function update(string $id, UpdatePageSlug $updatePageSlug): void;

    public function delete(string $id): void;
}