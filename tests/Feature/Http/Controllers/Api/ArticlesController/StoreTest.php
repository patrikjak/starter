<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticlesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class StoreTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testStore(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Test Article',
            'category' => $category->id,
            'author' => $author->id,
            'excerpt' => 'Test excerpt',
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Test content',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 5,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'article_category_id' => $category->id,
            'author_id' => $author->id,
            'excerpt' => 'Test excerpt',
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 5,
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreValidationFailsWithoutTitle(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'category' => $category->id,
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Test content',
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
    public function testStoreValidationFailsWithoutCategory(): void
    {
        $this->createAndActAsAdmin();

        $author = Author::factory()->create();

        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Test Article',
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Test content',
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
    public function testStoreValidationFailsWithoutAuthor(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();

        assert($category instanceof ArticleCategory);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Test Article',
            'category' => $category->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Test content',
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
    public function testStoreValidationFailsWithoutContent(): void
    {
        $this->createAndActAsAdmin();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Test Article',
            'category' => $category->id,
            'author' => $author->id,
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content.blocks']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $category = ArticleCategory::factory()->create();
        $author = Author::factory()->create();

        assert($category instanceof ArticleCategory);
        assert($author instanceof Author);

        $response = $this->postJson(route('admin.api.articles.store'), [
            'title' => 'Test Article',
            'category' => $category->id,
            'author' => $author->id,
            'excerpt' => 'Test excerpt',
            'content' => json_encode([
                'time' => 1747430180190,
                'blocks' => [
                    [
                        'id' => '1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Test content',
                        ],
                    ],
                ],
                'version' => '2.24.0',
            ]),
            'status' => ArticleStatus::DRAFT->value,
            'read_time' => 5,
        ]);

        $response->assertStatus(403);
    }
}
