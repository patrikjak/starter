<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\UsersController;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Auth\Notifications\RegisterInviteNotification;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Tests\TestCase;

final class InviteTest extends TestCase
{
    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteStoresTokenAndSendsNotification(): void
    {
        Notification::fake();

        $user = $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
            'role_id' => $user->role->id,
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
        $adminRole = Role::where('slug', 'admin')->firstOrFail();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
            'role_id' => $adminRole->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('register_invites', [
            'email' => 'newuser@example.com',
            'role_id' => $adminRole->id,
        ]);
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithInvalidEmailReturns422(): void
    {
        $user = $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'not-an-email',
            'role_id' => $user->role->id,
        ]);

        $response->assertUnprocessable();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithExistingEmailReturns422(): void
    {
        $user = $this->createAndActAsSuperAdmin();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'superadmin@example.com',
            'role_id' => $user->role->id,
        ]);

        $response->assertUnprocessable();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testInviteWithoutPermissionReturns403(): void
    {
        $user = $this->createAndActAsUser();

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
            'role_id' => $user->role->id,
        ]);

        $response->assertForbidden();
    }

    #[DefineEnvironment('enableUsers')]
    #[DefineEnvironment('enableRegisterViaInvitationFeature')]
    public function testAdminWithCreatePermissionCanInvite(): void
    {
        Notification::fake();

        $user = $this->createAndActAsAdmin(['create-user']);

        $response = $this->postJson(route('admin.api.users.invite'), [
            'email' => 'newuser@example.com',
            'role_id' => $user->role->id,
        ]);

        $response->assertOk();
    }
}
