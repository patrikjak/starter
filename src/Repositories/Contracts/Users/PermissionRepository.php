<?php

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Users\NewPermission;
use Patrikjak\Starter\Models\Users\Permission;

interface PermissionRepository
{
    /**
     * @return Collection<Permission>
     */
    public function getAll(): Collection;

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function getAllUnprotectedPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator;

    public function save(NewPermission $newPermission): void;

    public function updateByName(NewPermission $newPermission): void;

    public function deleteByName(string $name): void;
}