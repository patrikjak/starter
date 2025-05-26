<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Factories;

use Patrikjak\Auth\Database\Factories\UserFactory as UserFactoryBase;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

class UserFactory
{
    /**
     * @param array<string> $additionalPermissions
     */
    public static function createDefaultSuperAdminWithoutEvents(array $additionalPermissions = []): User
    {
        return User::withoutEvents(static function () use ($additionalPermissions) {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            $authorFactory = $authorFactory->withRole(RoleType::SUPERADMIN);
            assert($authorFactory instanceof UserFactoryBase);

            $user = $authorFactory->create([
                'id' => '9cef6fd7-a490-40db-b49f-ba27f691888c',
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ]);
            assert($user instanceof User);

            self::attachAdditionalPermissions($user->role, $additionalPermissions);

            return $user;
        });
    }

    /**
     * @param array<string> $additionalPermissions
     */
    public static function createDefaultAdminWithoutEvents(array $additionalPermissions = []): User
    {
        return User::withoutEvents(static function () use ($additionalPermissions) {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            $authorFactory = $authorFactory->withRole(RoleType::ADMIN);
            assert($authorFactory instanceof UserFactoryBase);

            $user = $authorFactory->create([
                'id' => 'e847cd49-f1ba-4c88-a607-c46ab5d5e98f',
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ]);
            assert($user instanceof User);

            self::attachAdditionalPermissions($user->role, $additionalPermissions);

            return $user;
        });
    }

    /**
     * @param array<string> $additionalPermissions
     */
    public static function createDefaultUserWithoutEvents(array $additionalPermissions = []): User
    {
        return User::withoutEvents(static function () use ($additionalPermissions) {
            $authorFactory = User::factory();
            assert($authorFactory instanceof UserFactoryBase);

            $user = $authorFactory->create([
                'id' => '25381043-0c05-480b-b4c7-5da10059a107',
                'name' => 'User',
                'email' => 'user@example.com',
            ]);
            assert($user instanceof User);

            self::attachAdditionalPermissions($user->role, $additionalPermissions);

            return $user;
        });
    }

    /**
     * @param array<string> $additionalPermissions
     */
    private static function attachAdditionalPermissions(Role $role, array $additionalPermissions): void
    {
        if (count($additionalPermissions) === 0) {
            return;
        }

        $permissionRepository = app(PermissionRepository::class);
        assert($permissionRepository instanceof PermissionRepository);

        $permissions = $permissionRepository->getAll()->mapWithKeys(
            static function (Permission $permission) {
                return [$permission->name => $permission->id];
            },
        );

        $mappedPermissions = collect($additionalPermissions)
            ->map(
                static function ($permission) use ($permissions) {
                    return $permissions->get($permission);
                },
            )
            ->filter()
            ->toArray();

        $roleRepository = app(RoleRepository::class);
        assert($roleRepository instanceof RoleRepository);

        $roleRepository->attachPermissions($role, $mappedPermissions);

        $role->refresh();
    }
}
