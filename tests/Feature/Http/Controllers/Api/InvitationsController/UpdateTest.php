<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\InvitationsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\TestCase;

final class UpdateTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testUpdateChangesRole(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000001',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'pending@example.com']),
            ['role_id' => $adminRole->id],
        );

        $response->assertOk();
        $this->assertDatabaseHas('register_invites', [
            'email' => 'pending@example.com',
            'role_id' => $adminRole->id,
        ]);
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testUpdateNonExistentReturnsNotFound(): void
    {
        $this->createAndActAsSuperAdmin();

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'nonexistent@example.com']),
            ['role_id' => $adminRole->id],
        );

        $response->assertNotFound();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testUpdateWithInvalidRoleReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000002',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'pending@example.com']),
            ['role_id' => 'non-existent-role'],
        );

        $response->assertUnprocessable();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testUpdateWithoutPermissionReturnsForbidden(): void
    {
        $this->createAndActAsUser();

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'pending@example.com']),
            ['role_id' => '00000000-0000-0000-0000-000000000002'],
        );

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminWithCreatePermissionCanUpdate(): void
    {
        $this->createAndActAsAdmin(['create-user']);

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000001',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $adminRole = Role::query()->where('slug', 'admin')->firstOrFail();

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'pending@example.com']),
            ['role_id' => $adminRole->id],
        );

        $response->assertOk();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminCannotAssignSuperadminRole(): void
    {
        $this->createAndActAsAdmin(['create-user']);

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000002',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $superadminRole = Role::query()->where('slug', 'superadmin')->firstOrFail();

        $response = $this->putJson(
            route('admin.api.users.invitations.update', ['email' => 'pending@example.com']),
            ['role_id' => $superadminRole->id],
        );

        $response->assertUnprocessable();
    }
}
