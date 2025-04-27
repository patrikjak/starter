<?php

namespace Patrikjak\Starter\Http\Controllers\Articles\Api;

use Patrikjak\Starter\Http\Requests\Articles\StoreArticleRequest;
use Patrikjak\Starter\Repositories\SupportsPagination;
use Patrikjak\Starter\Services\Articles\ArticleService;

class ArticlesController
{
    use SupportsPagination;

    public function store(StoreArticleRequest $request, ArticleService $articleService): void
    {
        $articleService->createArticle($request->getArticleInputData());
    }
}
