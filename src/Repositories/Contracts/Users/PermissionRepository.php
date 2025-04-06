<?php

namespace Patrikjak\Starter\Repositories\Contracts\Users;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Users\NewPermission;

interface PermissionRepository
{
    public function getAll(): Collection;

    public function save(NewPermission $newPermission): void;

    public function updateByName(string $name, array $description = []): void;

    public function deleteByName(string $name): void;
}