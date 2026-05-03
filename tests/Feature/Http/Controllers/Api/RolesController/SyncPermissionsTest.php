<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Tests\TestCase;

class SyncPermissionsTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testSyncPermissionsAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $roleRepository = app(RoleRepository::class);
        assert($roleRepository instanceof RoleRepository);
        $roleRepository->create('editor', 'Editor');

        $editorRole = Role::query()->where('slug', 'editor')->firstOrFail();

        $requestData = [
            'permission_create-article' => 'on',
            'permission_edit-article' => 'on',
            'permission_delete-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $editorRole->id]
        ), $requestData);

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.permissions_synced'),
            'level' => 'success',
        ]);

        $editorRole->refresh();

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'create-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'edit-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'delete-article')->first()->id,
        ]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testSyncPermissionsAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin([
            'manage-role',
        ]);

        $roleRepository = app(RoleRepository::class);
        assert($roleRepository instanceof RoleRepository);
        $roleRepository->create('editor', 'Editor');

        $editorRole = Role::query()->where('slug', 'editor')->firstOrFail();

        $requestData = [
            'permission_create-article' => 'on',
            'permission_edit-article' => 'on',
            'permission_delete-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $editorRole->id],
        ), $requestData);

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.permissions_synced'),
            'level' => 'success',
        ]);

        $editorRole->refresh();

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'create-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'edit-article')->first()->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => Permission::query()->where('name', 'delete-article')->first()->id,
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

    #[DefineEnvironment('enableUsers')]
    public function testSuperAdminCannotSyncPermissionsForOwnRole(): void
    {
        $user = $this->createAndActAsSuperAdmin();

        $role = $user->role;

        assert($role instanceof Role);

        $requestData = [
            'permission_create-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $role->id]
        ), $requestData);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testSuperAdminCannotSyncPermissionsForAnotherSuperAdminRole(): void
    {
        $this->createAndActAsSuperAdmin();

        $roleRepository = app(RoleRepository::class);
        assert($roleRepository instanceof RoleRepository);
        $roleRepository->create('superadmin2', 'Super Admin 2', true);

        $secondSuperadminRole = Role::query()->where('slug', 'superadmin2')->firstOrFail();

        $requestData = [
            'permission_create-article' => 'on',
        ];

        $response = $this->putJson(route(
            'admin.api.users.roles.permissions',
            ['role' => $secondSuperadminRole->id]
        ), $requestData);

        $response->assertForbidden();
    }
}
