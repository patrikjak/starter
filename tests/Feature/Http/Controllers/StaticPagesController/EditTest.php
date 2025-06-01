<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\StaticPagesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class EditTest extends TestCase
{
    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageCanBeRendered(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->get(route('admin.static-pages.create'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testCreatePageIsForbidden(): void
    {
        $this->createAndActAsAdmin();

        $this->get(route('admin.static-pages.create'))->assertForbidden();
    }
}