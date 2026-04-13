<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\UserRepository;
use Patrikjak\Starter\Services\Auth\AuthorizationService;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\ColumnVisibility;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class UsersTableProvider extends BasePaginatedTableProvider
{
    private bool $userCanEditUser;

    private bool $userCanViewSuperAdmin;

    /** @var array<string> */
    private array $superadminUserIds = [];

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AuthManager $authManager,
        private readonly AuthorizationService $authorizationService,
    ) {
        $this->userCanEditUser = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canEditUser(),
        );
        $this->userCanViewSuperAdmin = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canViewSuperAdmin(),
        );
    }

    public function getTableId(): string
    {
        return 'users-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return [
            'name' => __('pjstarter::pages.users.name'),
            'email' => __('pjstarter::pages.users.email'),
            'role' => __('pjstarter::pages.users.role'),
            'created_at' => __('pjstarter::general.created_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(function (User $user) {
            assert(is_string($user->role_name));

            if ($user->role->is_superadmin) {
                $this->superadminUserIds[] = $user->id;
            }

            $role = CellFactory::chip(
                $user->role_name,
                $user->role->is_superadmin ? Type::SUCCESS : Type::NEUTRAL,
            );

            return [
                'id' => $user->id,
                'name' => CellFactory::simple($user->name),
                'email' => CellFactory::simple($user->email),
                'role' => $role,
                'created_at' => CellFactory::simple($user->created_at->format('d.m.Y H:i')),
            ];
        })->toArray();
    }

    public function getColumnVisibility(): ColumnVisibility
    {
        return new ColumnVisibility(
            $this->getHeader(),
            ['created_at'],
        );
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        $actions = [];

        if ($this->userCanEditUser) {
            $actions[] = new Item(
                __('pjstarter::pages.users.change_role'),
                'change-role',
                Icon::EDIT,
                visible: function (array $row): bool {
                    $userId = $row['id'];
                    assert(is_string($userId));

                    if (in_array($userId, $this->superadminUserIds, true) && !$this->userCanViewSuperAdmin) {
                        return false;
                    }

                    return $this->authorizationService->getUserPermission(
                        static fn (User $user) => $user->id !== $userId,
                    );
                },
                inline: true,
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('admin.api.users.table-parts');

        $user = $this->authManager->user();
        $user = $user instanceof User ? $user : null;

        $users = $user?->canViewSuperAdmin() ?? true
            ? $this->userRepository->getAllPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            )
            : $this->userRepository->getAllExceptSuperAdminsPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            );

        return PaginatorFactory::createFromLengthAwarePaginator($users);
    }
}
