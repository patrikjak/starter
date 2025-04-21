<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Articles;

use Patrikjak\Starter\Dto\Articles\ArticleCategoryData;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface ArticleCategoryRepository extends SupportsPagination
{
    public function create(ArticleCategoryData $articleCategory): void;

    public function update(string $id, ArticleCategoryData $articleCategoryData): void;

    public function delete(string $id): void;
}