<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleCategoryFactory;
use Patrikjak\Starter\Tests\TestCase;

class ShowTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testShow(): void
    {
        $this->createAndActAsAdmin();

        $articleCategory = ArticleCategoryFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route(
            'admin.articles.categories.show',
            ['articleCategory' => $articleCategory->id],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}