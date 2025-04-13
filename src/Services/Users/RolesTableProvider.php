<?php

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class RolesTableProvider extends BasePaginatedTableProvider
{
    use StringCropper;

    private User $user;

    private bool $userCanViewSuperAdminRole = false;

    private bool $userCanViewAnyPermission = false;

    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly AuthManager $authManager,
    ) {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $this->user = $user;
        $this->userCanViewSuperAdminRole = $this->user->canViewSuperAdminRole();
        $this->userCanViewAnyPermission = $this->user->canViewAnyPermission();
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
            'permissions' => __('pjstarter::pages.users.roles.permissions'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(function (Role $role) {
            $rolePermissions = $this->roleRepository->getRolePermissions($role)
                ->map(static fn (Permission $permission) => $permission->description)
                ->implode(', ');

            $permissions = $this->getCroppedString($rolePermissions);

            return [
                'id' => CellFactory::simple($role->id),
                'name' => $this->user->canViewRole()
                    ? CellFactory::link($role->name, route('users.roles.show', ['role' => $role->id]))
                    : CellFactory::simple($role->name),
                'permissions' => CellFactory::simple($permissions),
            ];
        })->toArray();
    }

    public function getColumns(): array
    {
        $columns = ['name'];

        if ($this->userCanViewSuperAdminRole) {
            array_unshift($columns, 'id');
        }

        if ($this->userCanViewAnyPermission) {
            $columns[] = 'permissions';
        }

        return $columns;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        if (!$this->user->canManagePermissions()) {
            return [];
        }

        return [
            new Item(__('pjstarter::pages.users.roles.manage_permissions'), 'manage_permissions'),
        ];
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('api.users.roles.table-parts');

        $users = $this->userCanViewSuperAdminRole
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