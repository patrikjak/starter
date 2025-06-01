<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Articles\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Articles\StoreArticleCategoryRequest;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Services\Articles\ArticleCategoriesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class ArticleCategoriesController
{
    use TableParts;

    public function store(
        StoreArticleCategoryRequest $request,
        ArticleCategoryRepository $articleCategoryRepository,
    ): void {
        $articleCategoryRepository->create($request->getArticleCategoryData());
    }

    public function update(
        ArticleCategory $articleCategory,
        StoreArticleCategoryRequest $request,
        ArticleCategoryRepository $articleCategoryRepository,
    ): void {
        $articleCategoryRepository->update($articleCategory->id, $request->getArticleCategoryData());
    }

    public function destroy(
        ArticleCategory $articleCategory,
        ArticleCategoryRepository $articleCategoryRepository,
    ): JsonResponse {
        $articleCategoryRepository->delete($articleCategory->id);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.articles.categories.category_deleted'),
            'level' => 'success',
        ]);
    }

    public function tableParts(
        TableParametersRequest $request,
        ArticleCategoriesTableProvider $tableProvider,
    ): JsonResponse {
        return $this->getTableParts($request, $tableProvider);
    }
}
