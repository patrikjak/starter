<?php

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Auth\Models\Role;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Models\User;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class RolesTableProvider extends BasePaginatedTableProvider
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly AuthManager $authManager,
    ) {
    }

    public function getTableId(): string
    {
        return 'roles-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'id' => __('pjstarter::general.id'),
            'name' => __('pjstarter::pages.users.roles.name'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (Role $role) {
            return [
                'id' => CellFactory::simple($role->id),
                'name' => CellFactory::simple($role->name),
            ];
        })->toArray();
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('api.users.roles.table-parts');

        $currentUser = $this->authManager->user();
        assert($currentUser instanceof User);

        $users = $currentUser->hasRole(RoleType::SUPERADMIN)
            ? $this->roleRepository->getAllPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            )
            : $this->roleRepository->getAllWithoutSuperAdminPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            );

        return PaginatorFactory::createFromLengthAwarePaginator($users);
    }
}