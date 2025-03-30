<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Carbon\Carbon;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageCanBeRendered(): void
    {
        $this->actingAs($this->createSuperAdminUser());

        $response = $this->get(route('static-pages.create'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageIsForbidden(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->get(route('static-pages.create'))->assertForbidden();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 3, 30));
    }
}