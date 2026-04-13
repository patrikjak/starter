<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\TestCase;

class DestroyTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testDestroyAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        Role::insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'slug' => 'editor',
            'name' => 'Editor',
            'is_superadmin' => false,
        ]);

        $editorRole = Role::query()->where('slug', 'editor')->firstOrFail();

        $response = $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $editorRole->id]));

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_deleted'),
            'level' => 'success',
        ]);

        $this->assertDatabaseMissing('roles', ['id' => $editorRole->id]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testDestroyAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin(['delete-role']);

        Role::insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'slug' => 'editor',
            'name' => 'Editor',
            'is_superadmin' => false,
        ]);

        $editorRole = Role::query()->where('slug', 'editor')->firstOrFail();

        $response = $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $editorRole->id]));

        $response->assertOk();
        $this->assertDatabaseMissing('roles', ['id' => $editorRole->id]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testDestroyAsAdminWithoutPermissionReturns403(): void
    {
        $this->createAndActAsAdmin();

        Role::insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'slug' => 'editor',
            'name' => 'Editor',
            'is_superadmin' => false,
        ]);

        $editorRole = Role::query()->where('slug', 'editor')->firstOrFail();

        $response = $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $editorRole->id]));

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testCannotDestroyDefaultSuperadminRole(): void
    {
        $this->createAndActAsSuperAdmin();

        $superadminRole = Role::query()->where('slug', 'superadmin')->firstOrFail();

        $response = $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $superadminRole->id]));

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testCanDestroyNonDefaultSuperadminRoleWhenMultipleExist(): void
    {
        $this->createAndActAsSuperAdmin();

        Role::insert([
            [
                'id' => '00000000-0000-0000-0000-000000000003',
                'slug' => 'custom-superadmin-a',
                'name' => 'Custom Superadmin A',
                'is_superadmin' => true,
            ],
            [
                'id' => '00000000-0000-0000-0000-000000000004',
                'slug' => 'custom-superadmin-b',
                'name' => 'Custom Superadmin B',
                'is_superadmin' => true,
            ],
        ]);

        $roleA = Role::query()->where('slug', 'custom-superadmin-a')->firstOrFail();

        // 3 superadmin roles exist — deleting one is allowed
        $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $roleA->id]))
            ->assertOk();
    }

    #[DefineEnvironment('enableUsers')]
    public function testCannotDestroyOnlyRemainingNonDefaultSuperadminRole(): void
    {
        // Simulate a scenario where the default superadmin role was removed and only
        // one custom superadmin role remains — it should not be deletable.
        $this->createAndActAsAdmin(['delete-role']);

        // Remove the default superadmin role directly (bypassing the policy, simulating
        // a data-level operation or migration), leaving only admin as a default role.
        Role::query()->where('slug', 'superadmin')->delete();

        Role::insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'slug' => 'custom-superadmin',
            'name' => 'Custom Superadmin',
            'is_superadmin' => true,
        ]);

        $customRole = Role::query()->where('slug', 'custom-superadmin')->firstOrFail();

        // Only 1 superadmin role exists — cannot delete it
        $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $customRole->id]))
            ->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testCannotDestroyOwnRole(): void
    {
        $user = $this->createAndActAsAdmin(['delete-role']);

        $response = $this->deleteJson(route('admin.api.users.roles.destroy', ['role' => $user->role->id]));

        $response->assertForbidden();
    }
}
