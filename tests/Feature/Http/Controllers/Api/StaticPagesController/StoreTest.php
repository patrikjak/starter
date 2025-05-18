<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class StoreTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testSuccessfulStore(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->postJson(route('admin.api.static-pages.store'), [
            'name' => 'About us',
        ]);

        $this->assertDatabaseHas('static_pages', ['name' => 'About us']);
        $this->assertDatabaseHas('slugs', ['slug' => 'about-us']);
        $this->assertDatabaseHas('metadata', ['title' => 'About us | App']);
    }

    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('storeDataProvider')]
    public function testUnsuccessfulStore(string $name): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.static-pages.store'), ['name' => $name])
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('static_pages', 0);
        $this->assertDatabaseCount('slugs', 0);
        $this->assertDatabaseCount('metadata', 0);
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testStoreIsUnsuccessfulBecauseUnique(): void
    {
        $this->createAndActAsSuperAdmin();
        StaticPage::factory()->create(['name' => 'About us']);

        $response = $this->postJson(route('admin.api.static-pages.store'), ['name' => 'About us'])
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('static_pages', 1);
        $this->assertDatabaseCount('slugs', 1);
        $this->assertDatabaseCount('metadata', 1);
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testUnableStore(): void
    {
        $this->createAndActAsAdmin();

        $this->postJson(route('admin.api.static-pages.store'), ['name' => 'About us'])
            ->assertForbidden();
    }

    public function testCannotSeeStoreRoute(): void
    {
        $this->createAndActAsAdmin();

        $this->postJson('api/static-pages/store', ['name' => 'About us'])
            ->assertNotFound();
    }

    /**
     * @return iterable<array-key, array<string>>
     */
    public static function storeDataProvider(): iterable
    {
        yield 'Empty name' => ['name' => ''];
        yield 'Name is too long' => ['name' => str_repeat('a', 256)];
    }
}