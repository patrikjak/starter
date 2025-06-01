<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testUpdate(): void
    {
        $this->createAndActAsAdmin();

        $author = Author::factory()->create([
            'name' => 'Original Name',
        ]);

        assert($author instanceof Author);

        $response = $this->putJson(route('admin.api.authors.update', ['author' => $author->id]), [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Updated Name',
        ]);

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
            'name' => 'Original Name',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateValidationFailsWithoutName(): void
    {
        $this->createAndActAsAdmin();

        $author = Author::factory()->create();

        assert($author instanceof Author);

        $response = $this->putJson(route('admin.api.authors.update', ['author' => $author->id]), [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testUpdateFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $author = Author::factory()->create();

        assert($author instanceof Author);

        $response = $this->putJson(route('admin.api.authors.update', ['author' => $author->id]), [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(403);
    }
}
