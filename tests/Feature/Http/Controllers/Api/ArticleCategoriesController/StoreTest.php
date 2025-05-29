<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Tests\TestCase;

class StoreTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testStore(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->postJson(route('admin.api.articles.categories.store'), [
            'name' => 'Test Category',
            'description' => 'Test Category Description',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('article_categories', [
            'name' => 'Test Category',
            'description' => 'Test Category Description',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreValidationFailsWithoutName(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->postJson(route('admin.api.articles.categories.store'), [
            'description' => 'Test Category Description',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreValidationFailsWithDuplicateName(): void
    {
        $this->createAndActAsAdmin();

        ArticleCategory::factory()->create([
            'name' => 'Existing Category',
        ]);

        $response = $this->postJson(route('admin.api.articles.categories.store'), [
            'name' => 'Existing Category',
            'description' => 'Test Category Description',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $response = $this->postJson(route('admin.api.articles.categories.store'), [
            'name' => 'Test Category',
            'description' => 'Test Category Description',
        ]);

        $response->assertStatus(403);
    }
}
