<?php

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Starter\Dto\Users\FeaturePermissions;
use Patrikjak\Starter\Dto\Users\Permission;

trait PermissionsData
{
    public static function getPermissions(): array
    {
        return [
            new FeaturePermissions('static_pages', [
                new Permission(
                    'view',
                    [
                        'en' => 'View static pages',
                        'sk' => 'Zobraziť statické stránky',
                    ],
                ),
                new Permission(
                    'create',
                    [
                        'en' => 'Create static pages',
                        'sk' => 'Vytvoriť statické stránky',
                    ],
                    true,
                ),
                new Permission(
                    'edit',
                    [
                        'en' => 'Edit static pages',
                        'sk' => 'Upraviť statické stránky',
                    ],
                    true,
                ),
                new Permission(
                    'delete',
                    [
                        'en' => 'Delete static pages',
                        'sk' => 'Zmazať statické stránky',
                    ],
                    true,
                ),
            ]),
            new FeaturePermissions('metadata', [
                new Permission(
                    'view',
                    [
                        'en' => 'View metadata',
                        'sk' => 'Zobraziť SEO nastavenia',
                    ],
                ),
                new Permission(
                    'create',
                    [
                        'en' => 'Create metadata',
                        'sk' => 'Vytvoriť SEO nastavenia',
                    ],
                ),
                new Permission(
                    'edit',
                    [
                        'en' => 'Edit metadata',
                        'sk' => 'Upraviť SEO nastavenia',
                    ],
                ),
                new Permission(
                    'delete',
                    [
                        'en' => 'Delete metadata',
                        'sk' => 'Zmazať SEO nastavenia',
                    ],
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
                ),
            ]),
        ];
    }
}