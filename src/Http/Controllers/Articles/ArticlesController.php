<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Articles;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;
use Patrikjak\Starter\Services\Articles\ArticleCategoryService;
use Patrikjak\Starter\Services\Articles\ArticlesTableProvider;
use Patrikjak\Starter\Services\Authors\AuthorService;
use Patrikjak\Utils\Common\Dto\Image;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class ArticlesController
{
    public function index(TableParametersRequest $request, ArticlesTableProvider $tableProvider): View
    {
        return view('pjstarter::pages.articles.index', [
            'table' => $tableProvider->getTable(
                $request->getTableParameters($tableProvider->getTableId()),
            ),
        ]);
    }

    public function show(Article $article): View
    {
        return view('pjstarter::pages.articles.show', [
            'article' => $article,
        ]);
    }

    public function create(
        ArticleCategoryService $articleCategoryService,
        AuthorService $authorService,
    ): View {
        return view('pjstarter::pages.articles.create', [
            'articleCategories' => $articleCategoryService->getAllAsOptions(),
            'authors' => $authorService->getAllAsOptions(),
            'statuses' => ArticleStatus::asOptions(),
        ]);
    }

    public function edit(
        Article $article,
        ArticleCategoryService $articleCategoryService,
        AuthorService $authorService,
    ): View {
        return view('pjstarter::pages.articles.edit', [
            'article' => $article,
            'articleCategories' => $articleCategoryService->getAllAsOptions(),
            'authors' => $authorService->getAllAsOptions(),
            'statuses' => ArticleStatus::asOptions(),
            'featuredImage' => $article->featured_image === null
                ? []
                : [new Image($article->getFeaturedImagePath(), __('pjstarter::pages.articles.featured_image'))],
        ]);
    }
}
