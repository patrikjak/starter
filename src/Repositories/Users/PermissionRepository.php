<?php

namespace Patrikjak\Starter\Repositories\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Users\NewPermission;
use Patrikjak\Starter\Exceptions\Common\ModelIsNotInstanceOfBaseModelException;
use Patrikjak\Starter\Factories\ModelFactory;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository as PermissionRepositoryContract;

class PermissionRepository implements PermissionRepositoryContract
{
    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function getAll(): Collection
    {
        return $this->getModel()::all();
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getModel()::paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function getAllUnprotectedPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return $this->getModel()::where('protected', '=', 0)
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function save(NewPermission $newPermission): void
    {
        $permission = new ($this->getModel())();

        $permission->name = $newPermission->name;
        $permission->description = $newPermission->description;
        $permission->protected = $newPermission->protected;

        $permission->save();
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function updateByName(NewPermission $newPermission): void
    {
        $permission = $this->getModel()::where('name', $newPermission->name)->first();

        if (!$permission) {
            return;
        }

        $permission->description = $newPermission->description;
        $permission->protected = $newPermission->protected;

        $permission->save();
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function deleteByName(string $name): void
    {
        $permission = $this->getModel()::where('name', $name)->first();

        $permission?->delete();
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    private function getModel(): string
    {
        return ModelFactory::getPermissionModel();
    }
}