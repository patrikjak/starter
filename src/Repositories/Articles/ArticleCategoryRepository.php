<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Articles;

use Illuminate\Pagination\LengthAwarePaginator;
use Patrikjak\Starter\Dto\Articles\ArticleCategoryData;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as ArticleCategoryRepositoryContract;
use Patrikjak\Starter\Repositories\SupportsPagination;

class ArticleCategoryRepository implements ArticleCategoryRepositoryContract
{
    use SupportsPagination;

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getAllPaginatedByModel(ArticleCategory::class, $pageSize, $page, $refreshUrl);
    }

    public function create(ArticleCategoryData $articleCategory): void
    {
        $model = new ArticleCategory();

        $model->name = $articleCategory->name;
        $model->description = $articleCategory->description;

        $model->save();
    }

    public function update(string $id, ArticleCategoryData $articleCategoryData): void
    {
        $model = ArticleCategory::findOrFail($id);
        assert($model instanceof ArticleCategory);

        $model->name = $articleCategoryData->name;
        $model->description = $articleCategoryData->description;

        $model->save();
    }

    public function delete(string $id): void
    {
        ArticleCategory::destroy($id);
    }
}