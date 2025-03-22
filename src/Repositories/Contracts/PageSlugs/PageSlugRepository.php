<?php

namespace Patrikjak\Starter\Repositories\Contracts\PageSlugs;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;

interface PageSlugRepository
{
    public function getBySlug(string $slug): ?PageSlug;

    public function create(CreatePageSlug $createPageSlug): void;

    public function update(string $id, string $slug): void;

    public function delete(string $id): void;
}