<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Tests\TestCase;

class DeleteTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testDelete(): void
    {
        $this->createAndActAsAdmin();

        $author = Author::factory()->create([
            'name' => 'Author To Delete',
        ]);

        assert($author instanceof Author);

        $response = $this->deleteJson(route('admin.api.authors.destroy', ['author' => $author->id]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteNonExistentAuthor(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->deleteJson(route('admin.api.authors.destroy', ['author' => 'non-existent-id']));

        $response->assertStatus(404);
    }

    #[DefineEnvironment('enableArticles')]
    public function testDeleteFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $author = Author::factory()->create();

        assert($author instanceof Author);

        $response = $this->deleteJson(route('admin.api.authors.destroy', ['author' => $author->id]));

        $response->assertStatus(403);
    }
}