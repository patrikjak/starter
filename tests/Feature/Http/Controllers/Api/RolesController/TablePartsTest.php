<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class TablePartsTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testTablePartsAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.api.users.roles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
        $response->assertJsonStructure();
    }

    #[DefineEnvironment('enableUsers')]
    public function testTablePartsAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $response = $this->getJson(route('admin.api.users.roles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
    }

    #[DefineEnvironment('enableUsers')]
    public function testTablePartsAsAdminWithoutPermission(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->getJson(route('admin.api.users.roles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertOk();
    }

    #[DefineEnvironment('enableUsers')]
    public function testTablePartsAsUser(): void
    {
        $this->createAndActAsUser();

        $response = $this->getJson(route('admin.api.users.roles.table-parts', [
            'page' => 1,
            'perPage' => 10,
        ]));

        $response->assertForbidden();
    }
}
