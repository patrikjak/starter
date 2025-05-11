<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Carbon\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\StaticPages\StaticPage;
use Patrikjak\Starter\Tests\TestCase;

class CreateTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageCanBeRendered(): void
    {
        $this->actingAs($this->createSuperAdminUser());

        $response = $this->get(route('admin.static-pages.create'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageIsForbidden(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->get(route('admin.static-pages.create'))->assertForbidden();
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testEditPageCanBeRendered(): void
    {
        $this->actingAs($this->createSuperAdminUser());

        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasSlug()->create();
            assert($staticPage instanceof StaticPage);

            $response = $this->get(route('admin.static-pages.edit', ['staticPage' => $staticPage->id]));
            $response->assertOk();

            $this->assertMatchesHtmlSnapshot($response->getContent());
        });
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testEditPageIsForbidden(): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPage::withoutEvents(function (): void {
            $staticPage = StaticPage::factory()->hasSlug()->create();
            assert($staticPage instanceof StaticPage);

            $this->get(route('admin.static-pages.edit', ['staticPage' => $staticPage->id]))->assertForbidden();
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 3, 30));
    }
}