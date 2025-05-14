<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testIndex(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('admin.articles.categories.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndexWithCategory(): void
    {
        $this->actingAs($this->createAdminUser());
        ArticleCategory::factory()
            ->create();

        $response = $this->getJson(route('admin.articles.categories.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}