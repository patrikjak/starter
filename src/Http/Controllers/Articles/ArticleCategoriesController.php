<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Articles;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Services\Articles\ArticleCategoriesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class ArticleCategoriesController
{
    public function index(
        TableParametersRequest $request,
        ArticleCategoriesTableProvider $articleCategoriesTableProvider,
    ): View {
        return view('pjstarter::pages.articles.categories.index', [
            'table' => $articleCategoriesTableProvider->getTable(
                $request->getTableParameters($articleCategoriesTableProvider->getTableId()),
            ),
        ]);
    }

    public function show(ArticleCategory $articleCategory): View
    {
        return view('pjstarter::pages.articles.categories.show', [
            'articleCategory' => $articleCategory,
        ]);
    }

    public function create(): View
    {
        return view('pjstarter::pages.articles.categories.create');
    }

    public function edit(ArticleCategory $articleCategory): View
    {
        return view('pjstarter::pages.articles.categories.edit', [
            'articleCategory' => $articleCategory,
        ]);
    }
}
