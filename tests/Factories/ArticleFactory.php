<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Starter\Database\Factories\Articles\ArticleCategoryFactory;
use Patrikjak\Starter\Database\Factories\Articles\ArticleFactory as DatabaseArticleFactory;
use Patrikjak\Starter\Database\Factories\Authors\AuthorFactory;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Models\Metadata\Metadata;
use Patrikjak\Starter\Models\Slugs\Slug;

class ArticleFactory
{
    public static function createDefaultWithoutEvents(): Article
    {
        return Article::withoutEvents(static function () {
            $articleCategoryFactory = ArticleCategory::factory();
            assert($articleCategoryFactory instanceof ArticleCategoryFactory);

            $authorFactory = Author::factory();
            assert($authorFactory instanceof AuthorFactory);

            $articleCategoryFactory = $articleCategoryFactory
                ->defaultData()
                ->state([
                    'id' => '6c7af461-e5e2-499a-8884-1703e8663d7e',
                ]);
            $authorFactory = $authorFactory->defaultData();

            $articleFactory = Article::factory()
                ->has(Slug::factory()->state([
                    'id' => '0f5a12ec-0ab4-4410-9de8-b98858f62a32',
                    'slug' => 'super-cool-article',
                ]), 'slug')
                ->has(Metadata::factory()->state([
                    'id' => '991f82f8-bf98-4565-ba2f-975bb13b0fdf',
                    'title' => 'Super Cool Article | App',
                    'description' => 'This is a super cool article about testing.',
                    'keywords' => 'super, cool, article, app',
                    'canonical_url' => 'https://app.com/super-cool-article',
                ]), 'metadata')
                ->for($articleCategoryFactory, 'articleCategory')
                ->for($authorFactory, 'author');

            assert($articleFactory instanceof DatabaseArticleFactory);

            return $articleFactory
                ->defaultData()
                ->create();
        });
    }

    public static function createArticle(): Article
    {
        $articleFactory = Article::factory();
        assert($articleFactory instanceof DatabaseArticleFactory);

        $article = $articleFactory
            ->has(Slug::factory(), 'slug')
            ->has(Metadata::factory(), 'metadata')
            ->for(ArticleCategory::factory(), 'articleCategory')
            ->for(Author::factory(), 'author')
            ->create();

        assert($article instanceof Article);

        return $article;
    }
}