<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Auth\Database\Factories\UserFactory as UserFactoryBase;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\User;

class UserFactory
{
    public static function createDefaultSuperAdminWithoutEvents(): User
    {
        return User::withoutEvents(static function () {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            $authorFactory = $authorFactory->withRole(RoleType::SUPERADMIN);
            assert($authorFactory instanceof UserFactoryBase);

            return $authorFactory->create([
                'id' => '9cef6fd7-a490-40db-b49f-ba27f691888c',
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ]);
        });
    }

    public static function createDefaultAdminWithoutEvents(): User
    {
        return User::withoutEvents(static function () {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            $authorFactory = $authorFactory->withRole(RoleType::ADMIN);
            assert($authorFactory instanceof UserFactoryBase);

            return $authorFactory->create([
                'id' => 'e847cd49-f1ba-4c88-a607-c46ab5d5e98f',
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ]);
        });
    }

    public static function createDefaultUserWithoutEvents(): User
    {
        return User::withoutEvents(static function () {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            return $authorFactory
                ->create([
                    'id' => '25381043-0c05-480b-b4c7-5da10059a107',
                    'name' => 'User',
                    'email' => 'user@example.com',
                ]);
        });
    }
}
