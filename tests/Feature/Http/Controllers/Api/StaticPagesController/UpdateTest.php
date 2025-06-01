<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;

class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testSuccessfulUpdate(): void
    {
        $staticPage = StaticPage::factory()->create(['name' => 'Page']);
        assert($staticPage instanceof StaticPage);

        $this->createAndActAsSuperAdmin();

        $this->putJson(route('admin.api.static-pages.update', ['staticPage' => $staticPage->id]), [
            'name' => 'About us',
        ])->assertOk();

        $this->assertDatabaseHas('static_pages', ['name' => 'About us']);
        $this->assertDatabaseHas('slugs', ['slug' => 'page']);
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testUnableStore(): void
    {
        $staticPage = StaticPage::factory()->create(['name' => 'Page']);
        assert($staticPage instanceof StaticPage);

        $this->createAndActAsAdmin();

        $this->putJson(route('admin.api.static-pages.update', ['staticPage' => $staticPage->id]))
            ->assertForbidden();
    }

    public function testCannotSeeUpdateRoute(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->putJson('api/static-pages/update')->assertNotFound();
    }
}