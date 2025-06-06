<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleCategoryFactory;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testEdit(): void
    {
        $this->createAndActAsAdmin();

        $articleCategory = ArticleCategoryFactory::createDefaultWithoutEvents();

        $response = $this->getJson(route(
            'admin.articles.categories.edit',
            [
                'articleCategory' => $articleCategory->id,
            ],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}