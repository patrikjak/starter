<?php

namespace Patrikjak\Starter\Repositories\Contracts;

use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;

interface PageSlugRepository
{
    public function create(CreatePageSlug $createPageSlug): void;

    public function update(CreatePageSlug $createPageSlug, string $id): void;

    public function delete(string $id): void;
}