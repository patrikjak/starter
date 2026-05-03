<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Services\Users;

use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Services\Auth\AuthorizationService;
use Patrikjak\Starter\Support\StringCropper;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

class RolesTableProvider extends BasePaginatedTableProvider
{
    use StringCropper;

    private bool $userCanViewSuperAdminRole;

    private bool $userCanViewAnyPermission;

    private bool $userCanViewRole;

    private bool $userIsSuperAdmin;

    /** @var array<string, bool> */
    private array $isSuperAdminByRoleId = [];

    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly AuthorizationService $authorizationService,
    ) {
        $this->userCanViewSuperAdminRole = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canViewSuperAdminRole(),
        );
        $this->userCanViewAnyPermission = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canViewAnyPermission(),
        );
        $this->userCanViewRole = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->canViewRole(),
        );
        $this->userIsSuperAdmin = $this->authorizationService->getUserPermission(
            static fn (User $user) => $user->role->is_superadmin,
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

            $this->isSuperAdminByRoleId[$role->id] = $role->is_superadmin;

            return [
                'id' => $role->id,
                'name' => $this->userCanViewRole
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
        $actions = [];

        if ($this->authorizationService->getUserPermission(static fn (User $user) => $user->canManagePermissions())) {
            $actions[] = new Item(
                __('pjstarter::pages.users.roles.manage_permissions'),
                'manage_permissions',
                Icon::CHECK,
                visible: function (array $row): bool {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    if ($this->isSuperAdminByRoleId[$roleId] ?? false) {
                        return false;
                    }

                    if ($this->userIsSuperAdmin) {
                        return true;
                    }

                    return $this->authorizationService->getUserPermission(
                        static fn (User $user) => $user->role->id !== $roleId,
                    );
                },
                href: static function (array $row) {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    return route('admin.users.roles.permissions', ['role' => $roleId]);
                },
                inline: true,
            );
        }

        if (
            $this->authorizationService->getUserPermission(
                static fn (User $user) => $user->hasPermission(RolePolicy::FEATURE_NAME, BasePolicy::EDIT),
            )
        ) {
            $actions[] = new Item(
                __('pjstarter::general.edit'),
                'edit',
                Icon::EDIT,
                visible: function (array $row): bool {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    return $this->authorizationService->getUserPermission(
                        static fn (User $user) => $user->role->id !== $roleId,
                    );
                },
                href: static function (array $row) {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    return route('admin.users.roles.edit', ['role' => $roleId]);
                },
                inline: true,
            );
        }

        if (
            $this->authorizationService->getUserPermission(
                static fn (User $user) => $user->hasPermission(RolePolicy::FEATURE_NAME, BasePolicy::DELETE),
            )
        ) {
            $actions[] = new Item(
                __('pjstarter::general.delete'),
                'delete',
                Icon::TRASH,
                Type::DANGER,
                visible: function (array $row): bool {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    return $this->authorizationService->getUserPermission(
                        static fn (User $user) => $user->role->id !== $roleId,
                    );
                },
                href: static function (array $row) {
                    $roleId = $row['id'];
                    assert(is_string($roleId));

                    return route('admin.api.users.roles.destroy', ['role' => $roleId]);
                },
                method: 'DELETE',
                inline: true,
            );
        }

        return $actions;
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('admin.api.users.roles.table-parts');

        $paginator = $this->userCanViewSuperAdminRole
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

        return PaginatorFactory::createFromLengthAwarePaginator($paginator);
    }
}
