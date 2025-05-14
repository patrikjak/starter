<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Carbon\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\StaticPageFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRendered(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('admin.static-pages.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testPageCanBeRenderedWithData(): void
    {
        $this->actingAs($this->createAdminUser());

        StaticPageFactory::createDefaultWithoutEvents();

        $response = $this->get(route('admin.static-pages.index'))
            ->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testPageCannotBeFound(): void
    {
        $this->actingAs($this->createUser());

        $this->get('static-pages/')->assertNotFound();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 3, 30));
    }
}