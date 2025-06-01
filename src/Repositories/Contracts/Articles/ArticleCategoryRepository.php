<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Contracts\Articles;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Articles\ArticleCategoryData;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Repositories\Contracts\SupportsPagination;

interface ArticleCategoryRepository extends SupportsPagination
{
    /**
     * @return Collection<ArticleCategory>
     */
    public function getAll(): Collection;

    public function getById(string $id): ArticleCategory;

    public function create(ArticleCategoryData $articleCategory): void;

    public function update(string $id, ArticleCategoryData $articleCategoryData): void;

    public function delete(string $id): void;
}