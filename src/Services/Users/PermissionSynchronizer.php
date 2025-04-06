<?php

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Users\NewPermission;
use Patrikjak\Starter\Models\Users\Permission;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;

readonly class PermissionSynchronizer
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function synchronize(): void
    {
        $features = Permission::getFeatures();
        $featurePermissions = Permission::getFeaturePermissions();
        $permissionsDescriptions = Permission::getFeaturePermissionsDescriptions();

        $newPermissions = [];
        $savedPermissions = $this->permissionRepository->getAll()
            ->mapWithKeys(static fn (Permission $permission) => [$permission->name => $permission->description]);

        foreach ($features as $feature) {
            if (!array_key_exists($feature, $featurePermissions)) {
                continue;
            }

            foreach ($featurePermissions[$feature] as $availablePermission) {
                $permission = sprintf('%s-%s', $feature, $availablePermission);
                $description = $permissionsDescriptions[$feature][$availablePermission] ?? [];

                $newPermissions[$permission] = $description;
            }
        }

        $this->addNew(array_diff_key($newPermissions, $savedPermissions->toArray()));
        $this->update($this->getPermissionsToUpdate($savedPermissions, $permissionsDescriptions));
        $this->removeOld(array_diff_key($savedPermissions->toArray(), $newPermissions));
    }

    private function addNew(array $permissions): void
    {
        foreach ($permissions as $name => $description) {
            $this->permissionRepository->save(new NewPermission($name, $description));
        }
    }

    private function removeOld(array $permissions): void
    {
        foreach ($permissions as $name => $description) {
            $this->permissionRepository->deleteByName($name);
        }
    }

    private function getPermissionsToUpdate(Collection $savedPermissions, array $descriptions): array
    {
        $toUpdate = [];

        foreach ($savedPermissions as $name => $description) {
            $explodedPermission = explode('-', $name);
            $feature = $explodedPermission[0];
            $action = $explodedPermission[1];

            $newDescription = $descriptions[$feature][$action];

            if ($newDescription !== $description) {
                $toUpdate[$name] = $newDescription;
            }
        }

        return $toUpdate;
    }

    private function update(array $permissions): void
    {
        foreach ($permissions as $name => $description) {
            $this->permissionRepository->updateByName($name, $description);
        }
    }
}