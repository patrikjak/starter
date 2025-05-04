<?php

namespace Patrikjak\Starter\Http\Controllers\Articles\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Articles\StoreArticleRequest;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Repositories\SupportsPagination;
use Patrikjak\Starter\Services\Articles\ArticleService;
use Patrikjak\Starter\Services\Articles\ArticlesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class ArticlesController
{
    use SupportsPagination;
    use TableParts;

    public function store(StoreArticleRequest $request, ArticleService $articleService): void
    {
        $articleService->createArticle($request->getArticleInputData());
    }

    public function destroy(Article $article, ArticleService $articleService): JsonResponse
    {
        $articleService->deleteArticle($article);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.articles.article_deleted'),
            'level' => 'success',
        ]);
    }

    public function tableParts(TableParametersRequest $request, ArticlesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }

    public function uploadImage(Request $request, ArticleService $articleService): JsonResponse
    {
        $image = $request->file('image');

        if ($image === null) {
            return new JsonResponse([
                'success' => false,
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => asset(sprintf('storage/%s', $articleService->saveArticleImage($image))),
            ],
        ]);
    }

    public function fetchImage(Request $request, ArticleService $articleService): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => asset(sprintf(
                    'storage/%s',
                    $articleService->saveArticleImageFromUrl($request->input('url')),
                )),
            ],
        ]);
    }
}
