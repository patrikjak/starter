<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Starter\Database\Factories\Articles\ArticleCategoryFactory as DatabaseArticleCategoryFactory;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Slugs\Slug;

class ArticleCategoryFactory
{
    public static function createDefaultWithoutEvents(): ArticleCategory
    {
        return ArticleCategory::withoutEvents(static function () {
            $articleCategoryFactory = ArticleCategory::factory()
                ->has(Slug::factory()->state([
                    'id' => 'bbafd34c-0fe0-4f45-b2eb-c921fc9f95d6',
                    'slug' => 'testing-category',
                ]), 'slug')
                ->has(Metadata::factory()->state([
                    'id' => '226bdaf2-e0cf-4395-95d9-3ddc2d501875',
                    'title' => 'Testing Category | App',
                    'description' => 'Testing Category description',
                    'keywords' => 'testing category, app',
                    'canonical_url' => 'https://app.com/testing-category',
                ]), 'metadata');

            assert($articleCategoryFactory instanceof DatabaseArticleCategoryFactory);

            return $articleCategoryFactory
                ->defaultData()
                ->create();
        });
    }
}