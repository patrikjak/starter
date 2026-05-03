<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\TestCase;

class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testUpdateAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.roles.update', ['role' => $adminRole->id]), [
            'name' => 'Administrator',
        ]);

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_updated'),
            'level' => 'success',
        ]);

        $this->assertDatabaseHas('roles', ['id' => $adminRole->id, 'name' => 'Administrator']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin(['edit-role']);

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.roles.update', ['role' => $adminRole->id]), [
            'name' => 'Administrator',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('roles', ['id' => $adminRole->id, 'name' => 'Administrator']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateAsAdminWithoutPermissionReturns403(): void
    {
        $this->createAndActAsAdmin();

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.roles.update', ['role' => $adminRole->id]), [
            'name' => 'Administrator',
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateWithMissingNameReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.roles.update', ['role' => $adminRole->id]), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }
}
