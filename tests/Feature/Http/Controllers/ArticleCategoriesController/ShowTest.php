<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Tests\TestCase;

class ShowTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testShow(): void
    {
        $this->actingAs($this->createAdminUser());

        $category = ArticleCategory::factory()->create();
        assert($category instanceof ArticleCategory);

        $response = $this->getJson(route('admin.articles.categories.show', ['articleCategory' => $category->id]));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}