<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\ArticleFactory;
use Patrikjak\Starter\Tests\TestCase;

class ContentTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testContentAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $article = ArticleFactory::createArticle();

        $response = $this->getJson(route('admin.api.articles.content', $article));

        $response->assertOk();
        $response->assertJsonStructure([
            'content',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testContentAsAdminWithViewPermission(): void
    {
        $this->createAndActAsAdmin([
            'view-article',
        ]);

        $article = ArticleFactory::createArticle();

        $response = $this->getJson(route('admin.api.articles.content', $article));

        $response->assertOk();
        $response->assertJsonStructure([
            'content',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testContentWithNonExistentArticle(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.api.articles.content', 999));

        $response->assertNotFound();
    }
}