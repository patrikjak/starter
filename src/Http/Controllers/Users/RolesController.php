<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Users;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Services\Users\PermissionsService;
use Patrikjak\Starter\Services\Users\RolesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class RolesController
{
    public function index(TableParametersRequest $request, RolesTableProvider $rolesTableProvider): View
    {
        return view('pjstarter::pages.users.roles.index', [
            'rolesTable' => $rolesTableProvider->getTable(
                $request->getTableParameters($rolesTableProvider->getTableId()),
            ),
        ]);
    }

    public function show(Role $role, AuthManager $authManager): View
    {
        $user = $authManager->user();
        assert($user instanceof User);

        return view('pjstarter::pages.users.roles.show', [
            'role' => $role,
            'permissions' => $role->permissions->pluck('description')->implode(', '),
            'canSeeId' => $user->canViewSuperAdminRole(),
        ]);
    }

    public function permissions(
        Role $role,
        RoleRepository $roleRepository,
        PermissionsService $permissionsService
    ): View {
        return view('pjstarter::pages.users.roles.permissions', [
            'role' => $role,
            'availablePermissions' => $permissionsService->getAllAvailablePermissionsGroupedByFeature(),
            'assignedPermissions' => $roleRepository->getRolePermissions($role)->pluck('name')->toArray(),
        ]);
    }
}
