<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Tests\Factories\ArticleFactory;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testEdit(): void
    {
        $this->createAndActAsAdmin();

        $article = ArticleFactory::createDefaultWithoutEvents();

        assert($article instanceof Article);

        $response = $this->getJson(route('admin.articles.edit', ['article' => $article->id]));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testEditNonExistentArticle(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.articles.edit', ['article' => 'non-existent-id']));
        $response->assertStatus(404);
    }

    #[DefineEnvironment('enableArticles')]
    public function testEditFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $article = ArticleFactory::createDefaultWithoutEvents();

        assert($article instanceof Article);

        $response = $this->getJson(route('admin.articles.edit', ['article' => $article->id]));
        $response->assertStatus(403);
    }
}