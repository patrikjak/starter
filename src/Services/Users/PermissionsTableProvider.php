<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Auth\AuthManager;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;

final class PermissionsTableProvider extends BasePaginatedTableProvider
{
    public function __construct(
        private readonly PermissionRepository $permissionRepository,
        private readonly AuthManager $authManager,
    ) {
    }

    public function getTableId(): string
    {
        return 'permissions-table';
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): ?array
    {
        return [
            'name' => __('pjstarter::pages.users.permissions.name'),
            'description' => __('pjstarter::pages.users.permissions.description'),
            'protected' => __('pjstarter::pages.users.permissions.protected'),
            'created_at' => __('pjstarter::general.created_at'),
            'updated_at' => __('pjstarter::general.updated_at'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (Permission $permission) {
            return [
                'id' => $permission->id,
                'name' => CellFactory::simple($permission->name),
                'description' => CellFactory::simple($permission->description),
                'protected' => CellFactory::simple($permission->protected
                    ? __('pjstarter::pages.users.permissions.protected')
                    : __('pjstarter::pages.users.permissions.unprotected')
                ),
                'created_at' => CellFactory::simple($permission->created_at->format('d.m.Y H:i')),
                'updated_at' => CellFactory::simple($permission->updated_at->format('d.m.Y H:i')),
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $columns = ['description'];

        if ($user->canViewProtectedPermissions()) {
            $columns[] = 'name';
            $columns[] = 'protected';
        }

        $columns[] = 'created_at';
        $columns[] = 'updated_at';

        return $columns;
    }

    protected function getPaginator(): TablePaginator
    {
        $tablePartsRoute = route('api.users.permissions.table-parts');

        $user = $this->authManager->user();
        assert($user instanceof User);

        $users = $user->canViewProtectedPermissions()
            ? $this->permissionRepository->getAllPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            )
            : $this->permissionRepository->getAllUnprotectedPaginated(
                $this->getPageSize(),
                $this->getCurrentPage(),
                $tablePartsRoute,
            );

        return PaginatorFactory::createFromLengthAwarePaginator($users);
    }
}