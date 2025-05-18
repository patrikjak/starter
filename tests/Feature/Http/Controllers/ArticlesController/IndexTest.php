<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticlesController;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testIndex(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.articles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testIndexWithArticle(): void
    {
        $this->createAndActAsAdmin();

        ArticleFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route('admin.articles.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}