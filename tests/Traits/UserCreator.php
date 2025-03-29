<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Patrikjak\Auth\Models\Role;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Models\User;
use Patrikjak\Auth\Tests\Traits\UserCreator as AuthUserCreator;

trait UserCreator
{
    use AuthUserCreator {
        createUser as protected createUserFromAuth;
    }

    public function createAdminUser(): User
    {
        $this->createRole(RoleType::ADMIN);

        $user = new User();

        $user->name = 'Admin';
        $user->email = 'admin@example.com';
        $user->password = bcrypt('password');
        $user->role_id = RoleType::ADMIN->value;

        $user->save();

        return $user;
    }

    public function createSuperAdminUser(): User
    {
        $this->createRole(RoleType::SUPERADMIN);

        $user = new User();

        $user->name = 'Super Admin';
        $user->email = 'super@example.com';
        $user->password = bcrypt('password');
        $user->role_id = RoleType::SUPERADMIN->value;

        $user->save();

        return $user;
    }

    private function createRole(RoleType $roleType): void
    {
        Role::create(['id' => $roleType->value, 'name' => $roleType->name]);
    }
}