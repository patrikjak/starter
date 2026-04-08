<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\RolesController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\TestCase;

class StoreTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testStoreAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Editor',
        ]);

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_created'),
            'level' => 'success',
        ]);

        $this->assertDatabaseHas('roles', ['name' => 'Editor', 'slug' => 'editor']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreWithExplicitSlug(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Editor',
            'slug' => 'custom-editor',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('roles', ['name' => 'Editor', 'slug' => 'custom-editor']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreAsAdminWithPermission(): void
    {
        $this->createAndActAsAdmin(['create-role']);

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Editor',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('roles', ['name' => 'Editor', 'slug' => 'editor']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreAsAdminWithoutPermissionReturns403(): void
    {
        $this->createAndActAsAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Editor',
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreWithMissingNameReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreWithDuplicateSlugReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Superadmin Copy',
            'slug' => 'superadmin',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['slug']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testStoreWithInvalidSlugReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.roles.store'), [
            'name' => 'Editor',
            'slug' => 'Invalid Slug!',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['slug']);
    }
}
