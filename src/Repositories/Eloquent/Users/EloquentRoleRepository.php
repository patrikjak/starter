<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Repositories\Eloquent\Users;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Models\Role as BaseRole;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

readonly class EloquentRoleRepository implements RoleRepository
{
    public function create(string $slug, string $name, bool $isSuperadmin = false): void
    {
        $role = new Role();
        $role->slug = $slug;
        $role->name = $name;
        $role->is_superadmin = $isSuperadmin;
        $role->save();
    }

    public function firstOrCreate(string $slug, string $name, bool $isSuperadmin = false): BaseRole
    {
        $role = Role::query()->firstOrNew(['slug' => $slug]);

        if (!$role->exists) {
            $role->name = $name;
            $role->is_superadmin = $isSuperadmin;
            $role->save();
        }

        return $role;
    }

    public function getAll(): Collection
    {
        return Role::query()->get();
    }

    public function findBySlug(string $slug): ?BaseRole
    {
        return Role::query()->where('slug', $slug)->first();
    }

    public function findById(string $id): ?BaseRole
    {
        return Role::query()->find($id);
    }

    public function getAllPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Role::query()->paginate($pageSize, page: $page)->withPath($refreshUrl);
    }

    public function getAllWithoutSuperAdmin(): Collection
    {
        return Role::query()->where('is_superadmin', false)->get();
    }

    public function getAllWithoutSuperAdminPaginated(int $pageSize, int $page, string $refreshUrl): LengthAwarePaginator
    {
        return Role::query()->where('is_superadmin', false)
            ->paginate($pageSize, page: $page)
            ->withPath($refreshUrl);
    }

    /**
     * @inheritDoc
     */
    public function attachPermissions(Role $role, array $permissions): void
    {
        $role->permissions()->syncWithoutDetaching($permissions);
    }

    /**
     * @inheritDoc
     */
    public function syncPermissions(Role $role, array $permissions): void
    {
        $role->permissions()->sync($permissions);
    }

    public function getRolePermissions(Role $role): Collection
    {
        return $role->permissions;
    }

    public function countSuperadminRoles(): int
    {
        return Role::query()->where('is_superadmin', true)->count();
    }

    public function update(Role $role, string $name): void
    {
        $role->name = $name;
        $role->save();
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
