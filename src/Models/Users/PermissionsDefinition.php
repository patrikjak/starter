<?php

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Starter\Dto\Users\FeaturePermissions;
use Patrikjak\Starter\Dto\Users\Permission;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Metadata\MetadataPolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;
use Patrikjak\Starter\Policies\Users\PermissionPolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Policies\Users\UserPolicy;

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
            new FeaturePermissions(MetadataPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    [
                        'en' => 'View SEO settings',
                        'sk' => 'Zobraziť SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    [
                        'en' => 'View detail of SEO setting',
                        'sk' => 'Zobraziť detail SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    [
                        'en' => 'Edit SEO settings',
                        'sk' => 'Upraviť SEO nastavenia',
                    ],
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
            ]),
            new FeaturePermissions(UserPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    [
                        'en' => 'View users',
                        'sk' => 'Zobraziť používateľov'
                    ],
                    true,
                    [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    UserPolicy::VIEW_SUPERADMIN,
                    [
                        'en' => 'View super admin',
                        'sk' => 'Zobraziť super admin používateľov'
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
            ]),
            new FeaturePermissions(RolePolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    [
                        'en' => 'View user roles',
                        'sk' => 'Zobraziť používateľské role',
                    ],
                    true,
                    defaultRoles: [RoleType::SUPERADMIN, RoleType::ADMIN],
                ),
                new Permission(
                    RolePolicy::VIEW_SUPERADMIN,
                    [
                        'en' => 'View super admin role',
                        'sk' => 'Zobraziť super admin rolu',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
            ]),
            new FeaturePermissions(PermissionPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    [
                        'en' => 'View user permissions',
                        'sk' => 'Zobraziť používateľské oprávnenia',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
                new Permission(
                    PermissionPolicy::MANAGE_PROTECTED,
                    [
                        'en' => 'Manage protected permissions',
                        'sk' => 'Správa chránených oprávnení',
                    ],
                    true,
                    [RoleType::SUPERADMIN],
                ),
            ]),
        ];
    }
}