<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Tests\Factories\UserFactory;

trait WithTestUser
{
    /**
     * @param array<string> $additionalPermissions
     */
    public function createAndActAsSuperAdmin(array $additionalPermissions = []): User
    {
        $user = UserFactory::createDefaultSuperAdminWithoutEvents($additionalPermissions);

        $this->actingAs($user);

        return $user;
    }

    /**
     * @param array<string> $additionalPermissions
     */
    public function createAndActAsAdmin(array $additionalPermissions = []): User
    {
        $user = UserFactory::createDefaultAdminWithoutEvents($additionalPermissions);

        $this->actingAs($user);

        return $user;
    }

    /**
     * @param array<string> $additionalPermissions
     */
    public function createAndActAsUser(array $additionalPermissions = []): User
    {
        $user = UserFactory::createDefaultUserWithoutEvents($additionalPermissions);

        $this->actingAs($user);

        return $user;
    }
}