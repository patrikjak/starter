<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services\Users;

use Patrikjak\Starter\Dto\Users\Invitation;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\InvitationRepository;
use Patrikjak\Starter\Services\Auth\AuthorizationService;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\EmptyState;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class InvitationsTableProvider extends BasePaginatedTableProvider
{
    protected bool $userCanManageInvitations;

    public function __construct(
        private readonly InvitationRepository $invitationRepository,
        private readonly AuthorizationService $authorizationService,
    ) {
        $this->userCanManageInvitations = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canViewAnyInvitations(),
        );
    }

    public function getTableId(): string
    {
        return 'invitations-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return [
            'email' => __('pjstarter::pages.users.email'),
            'role' => __('pjstarter::pages.users.role'),
            'invited_at' => __('pjstarter::pages.users.invitations.invited_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (Invitation $invitation) {
            return [
                'id' => $invitation->email,
                'email' => CellFactory::simple($invitation->email),
                'role' => CellFactory::simple($invitation->roleName ?? '—'),
                'invited_at' => CellFactory::simple($invitation->invitedAt),
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        if (!$this->userCanManageInvitations) {
            return [];
        }

        return [
            new Item(
                __('pjstarter::pages.users.invitations.change_role'),
                'change-role',
                Icon::EDIT,
                inline: true,
            ),
            new Item(
                __('pjstarter::general.delete'),
                'delete',
                Icon::TRASH,
                Type::DANGER,
                href: static function (array $row) {
                    $email = $row['id'];
                    assert(is_string($email));

                    return route('admin.api.users.invitations.destroy', ['email' => $email]);
                },
                method: 'DELETE',
                inline: true,
            ),
        ];
    }

    public function getEmptyState(): EmptyState
    {
        return new EmptyState(
            __('pjstarter::pages.users.invitations.no_invitations'),
            __('pjstarter::pages.users.invitations.no_invitations_description'),
            svg('heroicon-s-mail')->toHtml(),
        );
    }

    protected function getPaginator(): TablePaginator
    {
        return PaginatorFactory::createFromLengthAwarePaginator(
            $this->invitationRepository->getAllPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                route('admin.api.users.invitations.table-parts'),
            ),
        );
    }
}
