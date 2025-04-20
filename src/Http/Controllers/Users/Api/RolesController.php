<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Controllers\Users\Api;

use Illuminate\Http\JsonResponse;
use Patrikjak\Starter\Http\Controllers\TableParts;
use Patrikjak\Starter\Http\Requests\Users\SyncPermissionsRequest;
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
