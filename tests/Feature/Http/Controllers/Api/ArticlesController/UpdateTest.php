<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\Article;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testUpdate(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);
        
        $article = Article::factory()->create([
            'title' => 'Original Title',
            'article_category_id' => $category->id,
            'author_id' => $author->id,
            'excerpt' => 'Original excerpt',
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 5,
        ]);

        assert($article instanceof Article);

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => 'Updated Title',
            'category' => $category->id,
            'author' => $author->id,
            'excerpt' => 'Updated excerpt',
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => [
                            'text' => 'Updated Header',
                            'level' => 2,
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Updated paragraph content.',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 10,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'article_category_id' => $category->id,
            'author_id' => $author->id,
            'excerpt' => 'Updated excerpt',
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 10,
        ]);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
            'title' => 'Original Title',
            'excerpt' => 'Original excerpt',
            'read_time' => 5,
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutTitle(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);
        
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'author_id' => $author->id,
        ]);

        assert($article instanceof Article);

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => '',
            'category' => $category->id,
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Content text',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutCategory(): void
    {
        $this->createAndActAsAdmin();

        $author = Author::factory()->create();

        assert($author instanceof Author);
        
        $article = Article::factory()->create([
            'author_id' => $author->id,
        ]);

        assert($article instanceof Article);

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => 'Article Title',
            'category' => '',
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Content text',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutAuthor(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();

        assert($category instanceof ArticleCategory);
        
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
        ]);

        assert($article instanceof Article);

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => 'Article Title',
            'category' => $category->id,
            'author' => '',
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Content text',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['author']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutContent(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);
        
        $article = Article::factory()->create([
            'article_category_id' => $category->id,
            'author_id' => $author->id,
        ]);

        assert($article instanceof Article);

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => 'Article Title',
            'category' => $category->id,
            'author' => $author->id,
            'content' => '',
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateFailsWithoutPermission(): void
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

        $response = $this->putJson(route('admin.api.articles.update', ['article' => $article->id]), [
            'title' => 'Updated Title',
            'category' => $category->id,
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Content text',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(403);
    }
}