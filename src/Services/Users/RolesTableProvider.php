<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Starter\Support\Traits\HandlesNullableAuthUser;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Cells\Simple;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class RolesTableProvider extends BasePaginatedTableProvider
{
    use HandlesNullableAuthUser;
    use StringCropper;

    private bool $userCanViewSuperAdminRole = false;

    private bool $userCanViewAnyPermission = false;

    public function __construct(
        private readonly RoleRepository $roleRepository,
        AuthManager $authManager,
    ) {
        $this->initializeUser($authManager);
        $this->userCanViewSuperAdminRole = $this->getUserPermission(
            static fn (User $user) => $user->canViewSuperAdminRole(),
        );
        $this->userCanViewAnyPermission = $this->getUserPermission(
            static fn (User $user) => $user->canViewAnyPermission(),
        );
    }

    public function getTableId(): string
    {
        return 'roles-table';
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
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
                'id' => CellFactory::simple((string) $role->id),
                'name' => $this->getUserPermission(static fn (User $user) => $user->canViewRole())
                    ? CellFactory::link($role->name, route('admin.users.roles.show', ['role' => $role->id]))
                    : CellFactory::simple($role->name),
                'permissions' => CellFactory::simple($permissions),
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
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
        if (!$this->getUserPermission(static fn (User $user) => $user->canManagePermissions())) {
            return [];
        }

        return [
            new Item(
                __('pjstarter::pages.users.roles.manage_permissions'),
                'manage_permissions',
                visible: function (array $row): bool {
                    $roleId = $row['id'];
                    assert($roleId instanceof Simple);

                    if ($this->userCanViewSuperAdminRole) {
                        return true;
                    }

                    return $this->getUserPermission(static fn (User $user) => $user->role->id !== (int) $roleId->value);
                },
                href: static function (array $row) {
                    $roleId = $row['id'];
                    assert($roleId instanceof Simple);

                    return route('admin.users.roles.permissions', ['role' => $roleId->value]);
                }
            ),
        ];
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('admin.api.users.roles.table-parts');

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