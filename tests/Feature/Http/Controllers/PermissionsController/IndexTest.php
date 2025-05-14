<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\PermissionsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testIndex(): void
    {
        $this->actingAs($this->createSuperAdminUser());

        $response = $this->getJson(route('admin.users.permissions.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    public function testIndexForbidden(): void
    {
        $this->actingAs($this->createAdminUser());

        $response = $this->getJson(route('admin.users.permissions.index'));
        $response->assertForbidden();
    }
}