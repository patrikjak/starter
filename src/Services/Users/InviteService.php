<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Services\InviteService as AuthInviteService;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\InvitationRepository;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

readonly class InviteService
{
    public function __construct(
        private AuthInviteService $authInviteService,
        private RoleRepository $roleRepository,
        private AuthManager $authManager,
        private InvitationRepository $invitationRepository,
    ) {
    }

    public function sendInvite(string $email, string $roleId): void
    {
        $this->authInviteService->sendInvite($email, $roleId);
    }

    public function delete(string $email): void
    {
        $this->invitationRepository->delete($email);
    }

    public function updateRole(string $email, string $roleId): void
    {
        $this->invitationRepository->updateRole($email, $roleId);
    }

    /**
     * @return Collection<int, Role>
     */
    public function getAvailableRoles(): Collection
    {
        $user = $this->authManager->user();
        $user = $user instanceof User ? $user : null;

        if ($user !== null && !$user->canViewSuperAdmin()) {
            return $this->roleRepository->getAllWithoutSuperAdmin();
        }

        return $this->roleRepository->getAll();
    }
}
