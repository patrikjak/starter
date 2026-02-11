<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Support\Traits;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Users\User;

trait HandlesNullableAuthUser
{
    private ?User $user;

    private function initializeUser(AuthManager $authManager): void
    {
        $user = $authManager->user();
        $this->user = $user instanceof User ? $user : null;
    }

    private function isAuthEnabled(): bool
    {
        return (bool) config('pjstarter.features.auth');
    }

    private function userCan(callable $permissionCheck): bool
    {
        if (!$this->isAuthEnabled()) {
            return true;
        }

        return $this->user instanceof User && $permissionCheck($this->user);
    }

    private function getUserPermission(callable $permissionCheck, bool $defaultWhenNoAuth = true): bool
    {
        if (!$this->isAuthEnabled()) {
            return $defaultWhenNoAuth;
        }

        return $this->user instanceof User ? $permissionCheck($this->user) : $defaultWhenNoAuth;
    }
}
