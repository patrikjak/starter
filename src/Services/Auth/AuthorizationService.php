<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Config\Repository as Config;
use Patrikjak\Starter\Models\Users\User;

readonly class AuthorizationService
{
    private ?User $user;

    public function __construct(AuthManager $authManager, private Config $config)
    {
        $user = $authManager->user();
        $this->user = $user instanceof User ? $user : null;
    }

    public function isAuthEnabled(): bool
    {
        return (bool) $this->config->get('pjstarter.features.auth');
    }

    public function userCan(callable $permissionCheck): bool
    {
        if (!$this->isAuthEnabled()) {
            return true;
        }

        return $this->user instanceof User && $permissionCheck($this->user);
    }

    public function getUserPermission(callable $permissionCheck, bool $defaultWhenNoAuth = true): bool
    {
        if (!$this->isAuthEnabled()) {
            return $defaultWhenNoAuth;
        }

        return $this->user instanceof User ? $permissionCheck($this->user) : $defaultWhenNoAuth;
    }
}
