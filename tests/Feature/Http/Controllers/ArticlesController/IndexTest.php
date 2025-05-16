<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticlesController;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 3, 30));
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndex(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('admin.articles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndexWithArticle(): void
    {
        $this->actingAs($this->createAdminUser());

        ArticleFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route('admin.articles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}