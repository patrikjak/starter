<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\PermissionsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\UserFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testIndex(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.users.permissions.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    public function testIndexForbidden(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.users.permissions.index'));
        $response->assertForbidden();
    }
}