<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleCategoryFactory;
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
        Carbon::setTestNow(Carbon::create(2025, 5, 16));

        $this->actingAs($this->createAdminUser());
        ArticleCategoryFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route('admin.articles.categories.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}