<?php

namespace Patrikjak\Starter\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Casts\JsonCast;

/**
 * @property string $name
 * @property string $description
 */
class Permission extends Model
{
    public const string STATIC_PAGES = 'static_pages';

    public const string METADATA = 'metadata';

    public const string USERS = 'users';

    public const string ROLES = 'roles';

    protected function casts(): array
    {
        return [
            'description' => JsonCast::class,
        ];
    }

    public static function getFeatures(): array
    {
        return [self::STATIC_PAGES, self::METADATA, self::USERS, self::ROLES];
    }

    public static function getFeaturePermissions(): array
    {
        return [
            self::STATIC_PAGES => ['view', 'create', 'edit', 'delete'],
            self::METADATA => ['view', 'create', 'edit', 'delete'],
            self::USERS => ['view'],
            self::ROLES => ['view'],
        ];
    }

    public static function getFeaturePermissionsDescriptions(): array
    {
        return [
            self::STATIC_PAGES => [
                'view' => [
                    'en' => 'View static pages',
                    'sk' => 'Zobraziť statické stránky',
                ],
                'create' => [
                    'en' => 'Create static pages',
                    'sk' => 'Vytvoriť statické stránky',
                ],
                'edit' => [
                    'en' => 'Edit static pages',
                    'sk' => 'Upraviť statické stránky',
                ],
                'delete' => [
                    'en' => 'Delete static pages',
                    'sk' => 'Zmazať statické stránky',
                ],
            ],
            self::METADATA => [
                'view' => [
                    'en' => 'View metadata',
                    'sk' => 'Zobraziť SEO nastavenia',
                ],
                'create' => [
                    'en' => 'Create metadata',
                    'sk' => 'Vytvoriť SEO nastavenia',
                ],
                'edit' => [
                    'en' => 'Edit metadata',
                    'sk' => 'Upraviť SEO nastavenia',
                ],
                'delete' => [
                    'en' => 'Delete metadata',
                    'sk' => 'Zmazať SEO nastavenia',
                ],
            ],
            self::USERS => [
                'view' => [
                    'en' => 'View users',
                    'sk' => 'Zobraziť používateľov',
                ],
            ],
            self::ROLES => [
                'view' => [
                    'en' => 'View user roles',
                    'sk' => 'Zobraziť používateľské role',
                ],
            ],
        ];
    }
}
