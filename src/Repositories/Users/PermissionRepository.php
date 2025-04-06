<?php

namespace Patrikjak\Starter\Repositories\Users;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Users\NewPermission;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository as PermissionRepositoryContract;

class PermissionRepository implements PermissionRepositoryContract
{
    public function getAll(): Collection
    {
        return Permission::all();
    }

    public function save(NewPermission $newPermission): void
    {
        $permission = new Permission();

        $permission->name = $newPermission->name;
        $permission->description = $newPermission->description;

        $permission->save();
    }

    public function updateByName(string $name, array $description = []): void
    {
        $permission = Permission::where('name', $name)->first();

        if (!$permission) {
            return;
        }

        $permission->description = $description;

        $permission->save();
    }

    public function deleteByName(string $name): void
    {
        $permission = Permission::where('name', $name)->first();

        $permission?->delete();
    }
}