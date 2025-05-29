<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\AuthorsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;

use Patrikjak\Starter\Tests\TestCase;

class StoreTest extends TestCase
{
    #[DefineEnvironment('enableArticles')]
    public function testStore(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->postJson(route('admin.api.authors.store'), [
            'name' => 'Test Author',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('authors', [
            'name' => 'Test Author',
        ]);
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreValidationFailsWithoutName(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->postJson(route('admin.api.authors.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableArticles')]
    public function testStoreFailsWithoutPermission(): void
    {
        $this->createAndActAsUser();

        $response = $this->postJson(route('admin.api.authors.store'), [
            'name' => 'Test Author',
        ]);

        $response->assertStatus(403);
    }
}
