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
        $this->actingAs($this->createSuperAdminUser());

        $this->postJson(route('api.static-pages.store'), [
            'name' => 'About us',
        ]);

        $this->assertDatabaseHas('static_pages', ['name' => 'About us']);
        $this->assertDatabaseHas('slugs', ['slug' => 'about-us']);
    }

    #[DefineEnvironment('enableStaticPages')]
    #[DataProvider('storeDataProvider')]
    public function testUnsuccessfulStore(string $name): void
    {
        $this->actingAs($this->createSuperAdminUser());

        $response = $this->postJson(route('api.static-pages.store'), ['name' => $name])
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('static_pages', 0);
        $this->assertDatabaseCount('slugs', 0);
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testStoreIsUnsuccessfulBecauseUnique(): void
    {
        $this->actingAs($this->createSuperAdminUser());
        StaticPage::factory()->create(['name' => 'About us']);

        $response = $this->postJson(route('api.static-pages.store'), ['name' => 'About us'])
            ->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('static_pages', 1);
        $this->assertDatabaseCount('slugs', 1);
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testUnableStore(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->postJson(route('api.static-pages.store'), ['name' => 'About us'])
            ->assertForbidden();
    }

    public function testCannotSeeStoreRoute(): void
    {
        $this->actingAs($this->createAdminUser());

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