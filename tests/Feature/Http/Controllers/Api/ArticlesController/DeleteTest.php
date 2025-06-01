<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class DeleteTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testDelete(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $article = Article::factory()->create([
            'title' => 'Article To Delete',
            'article_category_id' => $category->id,
            'author_id' => $author->id,
        ]);

        assert($article instanceof Article);

        $response = $this->deleteJson(route('admin.api.articles.destroy', ['article' => $article->id]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteNonExistentArticle(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->deleteJson(route('admin.api.articles.destroy', ['article' => 'non-existent-id']));

        $response->assertStatus(404);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'author_id' => $author->id,
        ]);

        assert($article instanceof Article);

        $response = $this->deleteJson(route('admin.api.articles.destroy', ['article' => $article->id]));

        $response->assertStatus(403);
    }
}
