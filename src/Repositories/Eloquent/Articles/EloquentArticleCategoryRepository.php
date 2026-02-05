<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Repositories\Eloquent\Articles;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Articles\ArticleCategoryData;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository as ArticleCategoryRepositoryContract;
use Patrikjak\Starter\Repositories\Eloquent\EloquentSupportsPagination;

class EloquentArticleCategoryRepository implements ArticleCategoryRepositoryContract
{
    use EloquentSupportsPagination;

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getAllPaginatedByModel(ArticleCategory::class, $pageSize, $page, $refreshUrl);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return ArticleCategory::all();
    }

    public function getById(string $id): ArticleCategory
    {
        return ArticleCategory::findOrFail($id);
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
