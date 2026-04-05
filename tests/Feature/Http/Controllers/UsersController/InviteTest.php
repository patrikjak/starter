<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\UsersController;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Auth\Notifications\RegisterInviteNotification;
use Patrikjak\Starter\Tests\TestCase;

final class InviteTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteStoresTokenAndSendsNotification(): void
    {
        Notification::fake();

        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('register_invites', ['email' => 'newuser@example.com']);
        Notification::assertCount(1);
        Notification::assertSentTo(
            new AnonymousNotifiable(),
            RegisterInviteNotification::class,
        );
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithRoleStoresRoleId(): void
    {
        Notification::fake();

        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
            'role_id' => 2,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('register_invites', ['email' => 'newuser@example.com', 'role_id' => 2]);
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithInvalidEmailReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'not-an-email',
        ]);

        $response->assertUnprocessable();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithExistingEmailReturns422(): void
    {
        $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'superadmin@example.com',
        ]);

        $response->assertUnprocessable();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithoutPermissionReturns403(): void
    {
        $this->createAndActAsUser();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminWithCreatePermissionCanInvite(): void
    {
        Notification::fake();

        $this->createAndActAsAdmin(['create-user']);

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
        ]);

        $response->assertOk();
    }
}
