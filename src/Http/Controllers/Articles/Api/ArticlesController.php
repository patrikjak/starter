<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Articles\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Articles\StoreArticleRequest;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Services\Articles\ArticleService;
use Patrikjak\Starter\Services\Articles\ArticlesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class ArticlesController
{
    use TableParts;

    public function store(StoreArticleRequest $request, ArticleService $articleService): void
    {
        $articleService->createArticle($request->getArticleInputData());
    }

    public function update(Article $article, StoreArticleRequest $request, ArticleService $articleService): void
    {
        $articleService->updateArticle(
            $article,
            $request->getArticleInputData(),
            $request->getFilesToDelete('featured_image'),
        );
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
        $this->ensureUserIsAuthorizedUploadImages($request);

        $image = $request->file('image');

        if ($image === null) {
            return new JsonResponse([
                'success' => false,
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => $articleService->saveArticleImage($image),
            ],
        ]);
    }

    public function fetchImage(Request $request, ArticleService $articleService): JsonResponse
    {
        $this->ensureUserIsAuthorizedUploadImages($request);

        return new JsonResponse([
            'success' => true,
            'file' => [
                'url' => $articleService->saveArticleImageFromUrl($request->input('url')),
            ],
        ]);
    }

    public function content(Article $article): JsonResponse
    {
        return new JsonResponse([
            'content' => $article->content->toJson(),
        ]);
    }

    private function ensureUserIsAuthorizedUploadImages(Request $request): void
    {
        if (
            $request->user()->cannot(BasePolicy::CREATE, Article::class)
            && $request->user()->cannot(BasePolicy::EDIT, Article::class)
        ) {
            abort(403);
        }
    }
}
