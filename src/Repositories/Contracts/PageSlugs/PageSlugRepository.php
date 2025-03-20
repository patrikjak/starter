<?php

namespace Patrikjak\Starter\Repositories\Contracts\PageSlugs;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;

interface PageSlugRepository
{
    public function create(CreatePageSlug $createPageSlug): void;

    public function update(string $id, string $slug): void;

    public function delete(string $id): void;
}