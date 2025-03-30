<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;

class DestroyTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testDestroy(): void
    {
        $staticPage = StaticPage::factory()->create(['name' => 'Page']);
        assert($staticPage instanceof StaticPage);

        $this->actingAs($this->createSuperAdminUser());

        $this->deleteJson(route('api.static-pages.destroy', ['staticPage' => $staticPage->id]))
            ->assertOk();

        $this->assertDatabaseMissing('static_pages', ['name' => 'page']);
        $this->assertDatabaseMissing('slugs', ['slug' => 'page']);
        $this->assertDatabaseMissing('metadata', ['metadatable_id' => $staticPage->id]);
    }
}