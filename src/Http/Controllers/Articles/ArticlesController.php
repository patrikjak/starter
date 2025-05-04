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
use Patrikjak\Starter\Services\Articles\ArticlesTableProvider;
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
        ArticleCategoryRepository $articleCategoryRepository,
        AuthorRepository $authorRepository,
    ): View {
        return view('pjstarter::pages.articles.create', [
            'articleCategories' => $articleCategoryRepository->getAll()
                ->mapwithkeys(static fn (ArticleCategory $item) => [$item->id => $item->name]),
            'authors' => $authorRepository->getAll()
                ->mapwithkeys(static fn (Author $item) => [$item->id => $item->name]),
            'statuses' => ArticleStatus::asOptions(),
        ]);
    }

    public function edit(): View
    {
        return view('pjstarter::pages.articles.edit');
    }
}
