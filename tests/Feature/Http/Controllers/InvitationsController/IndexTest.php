<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\InvitationsController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

final class IndexTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testIndex(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->getJson(route('admin.users.invitations.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testIndexWithPendingInvitations(): void
    {
        $this->createAndActAsSuperAdmin();

        $this->app->make('db')->table('register_invites')->insert([
            'email' => 'pending@example.com',
            'token' => 'test-token',
            'role_id' => '00000000-0000-0000-0000-000000000002',
            'created_at' => '2025-05-18 10:00:00',
        ]);

        $response = $this->getJson(route('admin.users.invitations.index'));
        $response->assertOk();

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testIndexWithoutAccessReturnsForbidden(): void
    {
        $this->createAndActAsUser();

        $response = $this->getJson(route('admin.users.invitations.index'));

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminWithCreatePermissionCanAccess(): void
    {
        $this->createAndActAsAdmin(['create-user']);

        $response = $this->getJson(route('admin.users.invitations.index'));

        $response->assertOk();
    }
}
