<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Tests\TestCase;

class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testUpdate(): void
    {
        $this->createAndActAsAdmin();

        $articleCategory = ArticleCategory::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original Description',
        ]);

        assert($articleCategory instanceof ArticleCategory);

        $response = $this->putJson(route('admin.api.articles.categories.update', ['articleCategory' => $articleCategory->id]), [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('article_categories', [
            'id' => $articleCategory->id,
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ]);

        $this->assertDatabaseMissing('article_categories', [
            'id' => $articleCategory->id,
            'name' => 'Original Name',
            'description' => 'Original Description',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutName(): void
    {
        $this->createAndActAsAdmin();

        $articleCategory = ArticleCategory::factory()->create();

        assert($articleCategory instanceof ArticleCategory);

        $response = $this->putJson(route('admin.api.articles.categories.update', ['articleCategory' => $articleCategory->id]), [
            'name' => '',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithDuplicateName(): void
    {
        $this->createAndActAsAdmin();

        $existingCategory = ArticleCategory::factory()->create([
            'name' => 'Existing Category',
        ]);

        $categoryToUpdate = ArticleCategory::factory()->create([
            'name' => 'Category To Update',
        ]);

        assert($existingCategory instanceof ArticleCategory);

        $response = $this->putJson(route('admin.api.articles.categories.update', ['articleCategory' => $categoryToUpdate->id]), [
            'name' => 'Existing Category',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $articleCategory = ArticleCategory::factory()->create();

        $response = $this->putJson(route('admin.api.articles.categories.update', ['articleCategory' => $articleCategory->id]), [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(403);
    }
}
