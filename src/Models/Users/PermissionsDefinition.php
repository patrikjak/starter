<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Starter\Dto\Users\FeaturePermissions;
use Patrikjak\Starter\Dto\Users\Permission;
use Patrikjak\Starter\Policies\Articles\ArticleCategoryPolicy;
use Patrikjak\Starter\Policies\Articles\ArticlePolicy;
use Patrikjak\Starter\Policies\Authors\AuthorPolicy;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Metadata\MetadataPolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;
use Patrikjak\Starter\Policies\Users\PermissionPolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Policies\Users\UserPolicy;

trait PermissionsDefinition
{
    /**
     * @return array<FeaturePermissions>
     */
    public static function getPermissions(): array
    {
        return [
            new FeaturePermissions(StaticPagePolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('static_pages.view_any'),
                    defaultRoles: ['superadmin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('static_pages.create'),
                    true,
                    ['superadmin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('static_pages.edit'),
                    true,
                    ['superadmin'],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    self::permissionDescriptions('static_pages.delete'),
                    true,
                    ['superadmin'],
                ),
            ]),
            new FeaturePermissions(MetadataPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('metadata.view_any'),
                    defaultRoles: ['superadmin'],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    self::permissionDescriptions('metadata.view'),
                    defaultRoles: ['superadmin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('metadata.edit'),
                    defaultRoles: ['superadmin'],
                ),
            ]),
            new FeaturePermissions(UserPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('users.view_any'),
                    true,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('users.create'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('users.edit'),
                    false,
                    ['superadmin'],
                ),
                new Permission(
                    UserPolicy::VIEW_SUPERADMIN,
                    self::permissionDescriptions('users.view_superadmin'),
                    true,
                    ['superadmin'],
                ),
            ]),
            new FeaturePermissions(RolePolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('roles.view_any'),
                    true,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    self::permissionDescriptions('roles.view'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    RolePolicy::VIEW_SUPERADMIN,
                    self::permissionDescriptions('roles.view_superadmin'),
                    true,
                    ['superadmin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('roles.create'),
                    false,
                    ['superadmin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('roles.edit'),
                    false,
                    ['superadmin'],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    self::permissionDescriptions('roles.delete'),
                    false,
                    ['superadmin'],
                ),
                new Permission(
                    RolePolicy::MANAGE,
                    self::permissionDescriptions('roles.manage'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    RolePolicy::MANAGE_PROTECTED,
                    self::permissionDescriptions('roles.manage_protected'),
                    true,
                    ['superadmin'],
                ),
            ]),
            new FeaturePermissions(PermissionPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('permissions.view_any'),
                    true,
                    ['superadmin'],
                ),
                new Permission(
                    PermissionPolicy::VIEW_PROTECTED,
                    self::permissionDescriptions('permissions.view_protected'),
                    true,
                    ['superadmin'],
                ),
            ]),
            new FeaturePermissions(AuthorPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('authors.view_any'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    self::permissionDescriptions('authors.view'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('authors.create'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('authors.edit'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    self::permissionDescriptions('authors.delete'),
                    false,
                    ['superadmin', 'admin'],
                ),
            ]),
            new FeaturePermissions(ArticlePolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('articles.view_any'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    self::permissionDescriptions('articles.view'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('articles.create'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('articles.edit'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    self::permissionDescriptions('articles.delete'),
                    false,
                    ['superadmin', 'admin'],
                ),
            ]),
            new FeaturePermissions(ArticleCategoryPolicy::FEATURE_NAME, [
                new Permission(
                    BasePolicy::VIEW_ANY,
                    self::permissionDescriptions('article_categories.view_any'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::VIEW,
                    self::permissionDescriptions('article_categories.view'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::CREATE,
                    self::permissionDescriptions('article_categories.create'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::EDIT,
                    self::permissionDescriptions('article_categories.edit'),
                    false,
                    ['superadmin', 'admin'],
                ),
                new Permission(
                    BasePolicy::DELETE,
                    self::permissionDescriptions('article_categories.delete'),
                    false,
                    ['superadmin', 'admin'],
                ),
            ]),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function permissionDescriptions(string $key): array
    {
        return [
            'en' => trans('pjstarter::permissions.' . $key, [], 'en'),
            'sk' => trans('pjstarter::permissions.' . $key, [], 'sk'),
        ];
    }
}
