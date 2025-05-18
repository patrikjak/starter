<?php

namespace Patrikjak\Starter\Tests\Factories;

use Carbon\CarbonImmutable;
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

            return $authorFactory
                ->withName('Super Admin')
                ->withRole(RoleType::SUPERADMIN)
                ->withEmail('super@admin.sk')
                ->create([
                    'id' => '9cef6fd7-a490-40db-b49f-ba27f691888c',
                    'created_at' => CarbonImmutable::create(2025, 5, 18),
                    'updated_at' => CarbonImmutable::create(2025, 5, 18),
                ]);
        });
    }

    public static function createDefaultAdminWithoutEvents(): User
    {
        return User::withoutEvents(static function () {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            return $authorFactory
                ->withName('Admin')
                ->withRole(RoleType::ADMIN)
                ->withEmail('admin@admin.sk')
                ->create([
                    'id' => 'e847cd49-f1ba-4c88-a607-c46ab5d5e98f',
                    'created_at' => CarbonImmutable::create(2025, 5, 18),
                    'updated_at' => CarbonImmutable::create(2025, 5, 18),
                ]);
        });
    }
}