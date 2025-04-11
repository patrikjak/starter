<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Tests\Traits\UserCreator as AuthUserCreator;
use Patrikjak\Starter\Models\Users\User;

trait UserCreator
{
    use AuthUserCreator {
        AuthUserCreator::createUser as protected createUserFromAuth;
    }

    public function createAdminUser(string $name = 'Admin', string $email = 'admin@example.com'): User
    {
        return User::factory()->withName($name)
            ->withEmail($email)
            ->withRole(RoleType::ADMIN)
            ->create();
    }

    public function createSuperAdminUser(string $name = 'Super Admin', string $email = 'super@example.com'): User
    {
        return User::factory()->withName($name)
            ->withEmail($email)
            ->withRole(RoleType::SUPERADMIN)
            ->create();
    }
}