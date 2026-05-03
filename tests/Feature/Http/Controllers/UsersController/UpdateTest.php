<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\UsersController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Tests\Factories\UserFactory;
use Patrikjak\Starter\Tests\TestCase;

final class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    public function testUpdateAsSuperAdmin(): void
    {
        $this->createAndActAsSuperAdmin();

        $targetUser = UserFactory::createDefaultAdminWithoutEvents();
        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.role_updated'),
            'level' => 'success',
        ]);

        $this->assertDatabaseHas('users', ['id' => $targetUser->id, 'role_id' => $adminRole->id]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateAsAdminWithPermission(): void
    {
        $targetUser = UserFactory::createDefaultUserWithoutEvents();
        $this->createAndActAsAdmin(['edit-user']);

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['id' => $targetUser->id, 'role_id' => $adminRole->id]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateWithoutPermissionReturns403(): void
    {
        $targetUser = UserFactory::createDefaultUserWithoutEvents();
        $this->createAndActAsAdmin();

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateOwnRoleReturns403(): void
    {
        $actingUser = $this->createAndActAsSuperAdmin();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $actingUser->id]), [
            'role_id' => $actingUser->role->id,
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateSuperadminUserWithoutViewSuperadminPermissionReturns403(): void
    {
        $this->createAndActAsSuperAdmin();

        $anotherSuperadmin = User::withoutEvents(static function () {
            $factory = User::factory();
            assert($factory instanceof \Patrikjak\Auth\Database\Factories\UserFactory);

            $factory = $factory->withRole('superadmin');
            assert($factory instanceof \Patrikjak\Auth\Database\Factories\UserFactory);

            $user = $factory->create([
                'name' => 'Another Superadmin',
                'email' => 'another@example.com',
            ]);
            assert($user instanceof User);

            return $user;
        });

        $this->createAndActAsAdmin(['edit-user']);
        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $anotherSuperadmin->id]), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    public function testSuperadminCanChangeSuperadminUsersRole(): void
    {
        $this->createAndActAsSuperAdmin();

        $anotherSuperadmin = User::withoutEvents(static function () {
            $factory = User::factory();
            assert($factory instanceof \Patrikjak\Auth\Database\Factories\UserFactory);

            $factory = $factory->withRole('superadmin');
            assert($factory instanceof \Patrikjak\Auth\Database\Factories\UserFactory);

            $user = $factory->create([
                'name' => 'Another Superadmin',
                'email' => 'another@example.com',
            ]);
            assert($user instanceof User);

            return $user;
        });

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $anotherSuperadmin->id]), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['id' => $anotherSuperadmin->id, 'role_id' => $adminRole->id]);
    }

    #[DefineEnvironment('enableUsers')]
    public function testAssigningSuperadminRoleWithoutViewSuperadminPermissionReturns422(): void
    {
        $targetUser = UserFactory::createDefaultUserWithoutEvents();
        $this->createAndActAsAdmin(['edit-user']);

        $superadminRole = Role::query()->where('slug', 'superadmin')->firstOrFail();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), [
            'role_id' => $superadminRole->id,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['role_id']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateWithInvalidRoleIdReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $targetUser = UserFactory::createDefaultAdminWithoutEvents();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), [
            'role_id' => 'non-existent-uuid',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['role_id']);
    }

    #[DefineEnvironment('enableUsers')]
    public function testUpdateWithMissingRoleIdReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $targetUser = UserFactory::createDefaultAdminWithoutEvents();

        $response = $this->putJson(route('admin.api.users.update', ['user' => $targetUser->id]), []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['role_id']);
    }
}
