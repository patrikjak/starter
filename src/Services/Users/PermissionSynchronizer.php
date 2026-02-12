<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Users;

use Illuminate\Support\Collection;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Dto\Users\FeaturePermissions;
use Patrikjak\Starter\Dto\Users\NewPermission;
use Patrikjak\Starter\Dto\Users\Permission;
use Patrikjak\Starter\Exceptions\Common\ModelIsNotInstanceOfBaseModelException;
use Patrikjak\Starter\Factories\ModelFactory;
use Patrikjak\Starter\Models\Users\Permission as PermissionModel;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Repositories\Contracts\Users\PermissionRepository;
use Patrikjak\Starter\Repositories\Contracts\Users\RoleRepository;

readonly class PermissionSynchronizer
{
    public function __construct(
        private PermissionRepository $permissionRepository,
        private RoleRepository $roleRepository,
    ) {
    }

    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public function synchronize(): void
    {
        $permissionModel = ModelFactory::getPermissionModel();

        $newPermissions = Collection::make($permissionModel::getPermissions())
            ->keyBy(static fn (FeaturePermissions $featurePermissions) => $featurePermissions->feature)
            ->map(
                static fn (FeaturePermissions $featurePermissions) => new Collection($featurePermissions->permissions)
                    ->keyBy(static fn (Permission $permission) => $permission->action)
            )
            ->toArray();

        $currentPermissions = $this->getCurrentPermissions();

        [$new, $redundant, $changed] = $this->getPermissionsDiff($newPermissions, $currentPermissions);

        foreach ($new as $feature => $permissions) {
            $this->savePermission(new FeaturePermissions($feature, $permissions));
        }

        foreach ($redundant as $featurePermissions) {
            $this->removePermissions($featurePermissions);
        }

        foreach ($changed as $feature => $permissions) {
            $this->editPermissions(new FeaturePermissions($feature, $permissions));
        }

        $this->insertDefaultPermissions($new);
    }

    /**
     * @param array<string, array<Permission>> $featurePermissions
     */
    public function insertDefaultPermissions(array $featurePermissions): void
    {
        $roles = $this->roleRepository->getAll();

        $permissions = $this->permissionRepository->getAll();
        $permissionIds = $permissions->mapWithKeys(
            static fn (PermissionModel $permission ) => [$permission->name => $permission->id]
        )->toArray();

        $rolePermissions = [
            RoleType::SUPERADMIN->name => [],
            RoleType::ADMIN->name => [],
            RoleType::USER->name => [],
        ];

        foreach ($featurePermissions as $feature => $permissions) {
            foreach ($permissions as $action => $permission) {
                assert($permission instanceof Permission);

                foreach ($permission->defaultRoles as $role) {
                    $rolePermissions[$role->name][] = $permissionIds[$this->getPermissionName($feature, $action)];
                }
            }
        }

        foreach ($roles as $role) {
            assert($role instanceof Role);

            $this->roleRepository->attachPermissions($role, $rolePermissions[$role->name]);
        }
    }

    /**
     * @param array<string, array<Permission>> $newFeatures
     * @param array<FeaturePermissions> $currentPermissions
     * @return array<int, array<string, array<Permission>|FeaturePermissions>>
     */
    private function getPermissionsDiff(array $newFeatures, array $currentPermissions): array
    {
        $toAdd = [];
        $toUpdate = [];

        foreach ($newFeatures as $feature => $featurePermissions) {
            foreach ($featurePermissions as $action => $permission) {
                $currentPermission = $currentPermissions[$feature]->permissions[$action] ?? null;

                if ($currentPermission === null) {
                    $toAdd[$feature][$action] = $permission;

                    continue;
                }

                if ($this->permissionIsChanged($permission, $currentPermission)) {
                    $toUpdate[$feature][$action] = $permission;
                }

                unset($currentPermissions[$feature]->permissions[$action]);
            }
        }

        foreach ($currentPermissions as $feature => $featurePermission) {
            if ($featurePermission->permissions === []) {
                unset($currentPermissions[$feature]);
            }
        }

        return [$toAdd, $currentPermissions, $toUpdate];
    }

    private function permissionIsChanged(Permission $newPermission, Permission $currentPermission): bool
    {
        return $newPermission->descriptions !== $currentPermission->descriptions
            || $newPermission->protected !== $currentPermission->protected;
    }

    /**
     * @return array<string, FeaturePermissions>
     */
    private function getCurrentPermissions(): array
    {
        $featuresToReturn = [];
        $currentFeatures = [];
        $features = $this->permissionRepository->getAll();

        foreach ($features as $feature) {
            $explodedName = explode('-', $feature->name);
            $action = $explodedName[0];
            $featureName = $explodedName[1];

            $currentFeatures[$featureName][$action] = new Permission(
                $action,
                $feature->allDescriptions(),
                $feature->protected,
            );
        }

        foreach ($currentFeatures as $featureName => $features) {
            $featuresToReturn[$featureName] = new FeaturePermissions($featureName, $features);
        }

        return $featuresToReturn;
    }

    private function savePermission(FeaturePermissions $featurePermissions): void
    {
        foreach ($featurePermissions->permissions as $permission) {
            $name = $this->getPermissionName($featurePermissions->feature, $permission->action);

            $this->permissionRepository->save(new NewPermission(
                $name,
                $permission->descriptions,
                $permission->protected,
            ));
        }
    }

    private function editPermissions(FeaturePermissions $featurePermissions): void
    {
        foreach ($featurePermissions->permissions as $permission) {
            $name = $this->getPermissionName($featurePermissions->feature, $permission->action);

            $this->permissionRepository->updateByName(new NewPermission(
                $name,
                $permission->descriptions,
                $permission->protected,
            ));
        }
    }

    private function removePermissions(FeaturePermissions $featurePermissions): void
    {
        foreach ($featurePermissions->permissions as $permission) {
            $name = $this->getPermissionName($featurePermissions->feature, $permission->action);

            $this->permissionRepository->deleteByName($name);
        }
    }

    private function getPermissionName(string $feature, string $action): string
    {
        return sprintf('%s-%s', $action, $feature);
    }
}