<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Services\InviteService as AuthInviteService;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

final readonly class InviteService
{
    public function __construct(
        private AuthInviteService $authInviteService,
        private RoleRepository $roleRepository,
        private AuthManager $authManager,
    ) {
    }

    public function sendInvite(string $email, ?int $roleId = null): void
    {
        $this->authInviteService->sendInvite($email, $roleId);
    }

    public function getAvailableRoles(): Collection
    {
        $user = $this->authManager->user();
        $user = $user instanceof User ? $user : null;

        $roles = $this->roleRepository->getAll();

        if ($user !== null && !$user->canViewSuperAdmin()) {
            $roles = $roles->filter(
                static fn ($role) => $role->name !== RoleType::SUPERADMIN->name,
            )->values();
        }

        return $roles;
    }
}
