<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers;

use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class DashboardController
{
    public function index(): View
    {
        $articlesEnabled = (bool) config('pjstarter.features.articles');
        $staticPagesEnabled = (bool) config('pjstarter.features.static_pages');

        $articleCount = $articlesEnabled ? Article::count() : null;
        $categoryCount = $articlesEnabled ? ArticleCategory::count() : null;
        $authorCount = $articlesEnabled ? Author::count() : null;
        $staticPageCount = $staticPagesEnabled ? StaticPage::count() : null;

        $hasStats = $articleCount !== null
            || $categoryCount !== null
            || $authorCount !== null
            || $staticPageCount !== null;

        return view('pjstarter::pages.dashboard', [
            'articleCount' => $articleCount,
            'categoryCount' => $categoryCount,
            'authorCount' => $authorCount,
            'staticPageCount' => $staticPageCount,
            'hasStats' => $hasStats,
        ]);
    }
}
