<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\UserRepository;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class UsersTableProvider extends BasePaginatedTableProvider
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AuthManager $authManager,
    ) {
    }

    public function getTableId(): string
    {
        return 'users-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
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
        return $this->getPageData()->map(static function (User $user) {
            $role = CellFactory::chip(
                $user->role_name,
                match ($user->role_name) {
                    RoleType::SUPERADMIN->name, RoleType::ADMIN->name => Type::SUCCESS,
                    RoleType::USER->name => Type::NEUTRAL,
                },
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

    public function showOrder(): bool
    {
        return true;
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('api.users.table-parts');

        $user = $this->authManager->user();
        assert($user instanceof User);

        $users = $user->canViewSuperAdmin()
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