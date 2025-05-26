<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Tests\TestCase;

class ManageTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testManagePermissionsAsSuperAdmin(): void
    {
        $user = $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route(
            'admin.users.roles.permissions',
            ['role' => $user->role->id],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    public function testManagePermissionsAsAdmin(): void
    {
        $user = $this->createAndActAsAdmin();

        $response = $this->getJson(route(
            'admin.users.roles.permissions',
            ['role' => $user->role->id],
        ));

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testManagePermissionsAsUser(): void
    {
        $user = $this->createAndActAsUser();

        $response = $this->getJson(route(
            'admin.users.roles.permissions',
            ['role' => $user->role->id],
        ));

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testManagePermissionsAsAdminWithPermissions(): void
    {
        $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $response = $this->getJson(route(
            'admin.users.roles.permissions',
            ['role' => RoleType::USER],
        ));

        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    public function testCannotManagePermissionsForRoleSameAsUser(): void
    {
        $user = $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $response = $this->getJson(route(
            'admin.users.roles.permissions',
            ['role' => $user->role->id],
        ));

        $response->assertForbidden();
    }
}