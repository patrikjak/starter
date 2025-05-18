<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Traits;

use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Tests\Factories\UserFactory;

trait WithTestUser
{
    public function createAndActAsSuperAdmin(): User
    {
        $user = UserFactory::createDefaultSuperAdminWithoutEvents();

        $this->actingAs($user);

        return $user;
    }

    public function createAndActAsAdmin(): User
    {
        $user = UserFactory::createDefaultAdminWithoutEvents();

        $this->actingAs($user);

        return $user;
    }

    public function createAndActAsUser(): User
    {
        $user = UserFactory::createDefaultUserWithoutEvents();

        $this->actingAs($user);

        return $user;
    }
}