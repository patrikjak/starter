<?php

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Dto\Users\FeaturePermissions;
use Patrikjak\Starter\Dto\Users\Permission;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;

trait PermissionsDefinition
{
    public static function getPermissions(): array
    {
        return [
            new FeaturePermissions(StaticPagePolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    [
                        'en' => 'View static pages',
                        'sk' => 'Zobraziť statické stránky',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    [
                        'en' => 'Create static pages',
                        'sk' => 'Vytvoriť statické stránky',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    [
                        'en' => 'Edit static pages',
                        'sk' => 'Upraviť statické stránky',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    [
                        'en' => 'Delete static pages',
                        'sk' => 'Zmazať statické stránky',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
            ]),
            new FeaturePermissions('metadata', [
                new Permission(
                    'view',
                    [
                        'en' => 'View metadata',
                        'sk' => 'Zobraziť SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    'create',
                    [
                        'en' => 'Create metadata',
                        'sk' => 'Vytvoriť SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    'edit',
                    [
                        'en' => 'Edit metadata',
                        'sk' => 'Upraviť SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    'delete',
                    [
                        'en' => 'Delete metadata',
                        'sk' => 'Zmazať SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
            ]),
            new FeaturePermissions('users', [
                new Permission(
                    'view',
                    [
                        'en' => 'View users',
                        'sk' => 'Zobraziť používateľov'
                    ],
                    true,
                    [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
            ]),
            new FeaturePermissions('roles', [
                new Permission(
                    'view',
                    [
                        'en' => 'View user roles',
                        'sk' => 'Zobraziť používateľské role',
                    ],
                    true,
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
            ]),
            new FeaturePermissions('permissions', [
                new Permission(
                    'view',
                    [
                        'en' => 'View user permissions',
                        'sk' => 'Zobraziť používateľské oprávnenia',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
            ]),
        ];
    }
}