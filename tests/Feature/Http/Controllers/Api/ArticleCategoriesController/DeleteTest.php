<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\ArticleCategoriesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Starter\Tests\TestCase;

class DeleteTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testDelete(): void
    {
        $this->createAndActAsAdmin();

        $articleCategory = ArticleCategory::factory()->create([
            'name' => 'Category To Delete',
            'description' => 'Category Description',
        ]);

        assert($articleCategory instanceof ArticleCategory);

        $response = $this->deleteJson(route(
            'admin.api.articles.categories.destroy',
            ['articleCategory' => $articleCategory->id],
        ));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('article_categories', [
            'id' => $articleCategory->id,
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteNonExistentCategory(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->deleteJson(route(
            'admin.api.articles.categories.destroy',
            ['articleCategory' => 'non-existent-id'],
        ));

        $response->assertStatus(404);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $articleCategory = ArticleCategory::factory()->create();

        assert($articleCategory instanceof ArticleCategory);

        $response = $this->deleteJson(route(
            'admin.api.articles.categories.destroy',
            ['articleCategory' => $articleCategory->id],
        ));

        $response->assertStatus(403);
    }
}