<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\Factories\UserFactory;
use Patrikjak\Starter\Tests\TestCase;

class SyncPermissionsTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testSyncPermissionsAsSuperAdmin(): void
    {
        $user = $this->createAndActAsSuperAdmin();
        $role = $user->role;

        $requestData = [
            'permission_create-article' => 'on',
            'permission_edit-article' => 'on',
            'permission_delete-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $role->id]
        ), $requestData);

        $response->assertOk();
        $response->assertJson([
            'message' => __('pjstarter::pages.users.roles.permissions_synced'),
        ]);

        $role->refresh();

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'create-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'edit-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'delete-article')->first()->id,
        ]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testSyncPermissionsAsAdminWithPermission(): void
    {
        $user = $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $basicUser = UserFactory::createDefaultUserWithoutEvents();

        $role = $user->role;

        $requestData = [
            'permission_create-article' => 'on',
            'permission_edit-article' => 'on',
            'permission_delete-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $basicUser->role->id],
        ), $requestData);

        $response->assertOk();
        $response->assertJson([
            'message' => __('pjstarter::pages.users.roles.permissions_synced'),
        ]);

        $role->refresh();

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'create-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'edit-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => Permission::where('name', 'delete-article')->first()->id,
        ]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testCannotSyncPermissionsForOwnRole(): void
    {
        $user = $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $role = $user->role;

        assert($role instanceof Role);

        $requestData = [
            'permission_create-article' => 'on',
            'permission_edit-article' => 'on',
            'permission_delete-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $role->id]
        ), $requestData);

        $response->assertForbidden();
    }
}
