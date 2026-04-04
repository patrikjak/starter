<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services;

use Patrikjak\Starter\Dto\Dashboard\DashboardStats;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

readonly class DashboardService
{
    public function getStats(): DashboardStats
    {
        $articlesEnabled = (bool) config('pjstarter.features.articles');
        $staticPagesEnabled = (bool) config('pjstarter.features.static_pages');

        $articleCount = $articlesEnabled ? Article::query()->count() : null;
        $categoryCount = $articlesEnabled ? ArticleCategory::query()->count() : null;
        $authorCount = $articlesEnabled ? Author::query()->count() : null;
        $staticPageCount = $staticPagesEnabled ? StaticPage::query()->count() : null;

        $hasStats = $articleCount !== null
            || $categoryCount !== null
            || $authorCount !== null
            || $staticPageCount !== null;

        return new DashboardStats($articleCount, $categoryCount, $authorCount, $staticPageCount, $hasStats);
    }
}
