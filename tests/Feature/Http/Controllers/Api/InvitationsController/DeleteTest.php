<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\Api\InvitationsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

final class DeleteTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testDestroyDeletesInvitation(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000002',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $response = $this->deleteJson(
            route('admin.api.users.invitations.destroy', ['email' => 'pending@example.com']),
        );

        $response->assertOk();
        $this->assertDatabaseMissing('register_invites', ['email' => 'pending@example.com']);
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testDestroyNonExistentReturnsNotFound(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->deleteJson(
            route('admin.api.users.invitations.destroy', ['email' => 'nonexistent@example.com']),
        );

        $response->assertNotFound();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testDestroyWithoutPermissionReturnsForbidden(): void
    {
        $this->createAndActAsUser();

        $response = $this->deleteJson(
            route('admin.api.users.invitations.destroy', ['email' => 'pending@example.com']),
        );

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminWithCreatePermissionCanDestroy(): void
    {
        $this->createAndActAsAdmin(['create-user']);

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000002',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $response = $this->deleteJson(
            route('admin.api.users.invitations.destroy', ['email' => 'pending@example.com']),
        );

        $response->assertOk();
        $this->assertDatabaseMissing('register_invites', ['email' => 'pending@example.com']);
    }
}
