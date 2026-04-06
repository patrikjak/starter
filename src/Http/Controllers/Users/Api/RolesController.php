<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Users\CreateRoleRequest;
use Patrikjak\Starter\Http\Requests\Users\SyncPermissionsRequest;
use Patrikjak\Starter\Http\Requests\Users\UpdateRoleRequest;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;
use Patrikjak\Starter\Services\Users\PermissionsService;
use Patrikjak\Starter\Services\Users\RolesTableProvider;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class RolesController
{
    use TableParts;

    public function tableParts(TableParametersRequest $request, RolesTableProvider $tableProvider): JsonResponse
    {
        return $this->getTableParts($request, $tableProvider);
    }

    public function store(CreateRoleRequest $request, RoleRepository $roleRepository): JsonResponse
    {
        $roleRepository->create($request->getSlug(), $request->getName());

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_created'),
            'level' => 'success',
        ]);
    }

    public function update(Role $role, UpdateRoleRequest $request, RoleRepository $roleRepository): JsonResponse
    {
        $roleRepository->update($role, $request->getName());

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_updated'),
            'level' => 'success',
        ]);
    }

    public function destroy(Role $role, RoleRepository $roleRepository): JsonResponse
    {
        $roleRepository->delete($role);

        return new JsonResponse([
            'title' => __('pjstarter::general.success'),
            'message' => __('pjstarter::pages.users.roles.role_deleted'),
            'level' => 'success',
        ]);
    }

    public function syncPermissions(
        Role $role,
        SyncPermissionsRequest $request,
        RoleRepository $roleRepository,
        PermissionsService $permissionsService,
    ): JsonResponse {
        $permissions = $permissionsService->getAvailablePermissionsFromNames($request->getPermissions());

        $roleRepository->syncPermissions($role, $permissions->pluck('id')->toArray());

        return new JsonResponse([
            'message' => __('pjstarter::pages.users.roles.permissions_synced'),
        ]);
    }
}
