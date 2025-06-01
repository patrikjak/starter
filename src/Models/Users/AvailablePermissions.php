<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Starter\Policies\Articles\ArticleCategoryPolicy;
use Patrikjak\Starter\Policies\Articles\ArticlePolicy;
use Patrikjak\Starter\Policies\Authors\AuthorPolicy;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\Metadata\MetadataPolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;
use Patrikjak\Starter\Policies\Users\PermissionPolicy;
use Patrikjak\Starter\Policies\Users\RolePolicy;
use Patrikjak\Starter\Policies\Users\UserPolicy;

trait AvailablePermissions
{
    public function canViewAnyStaticPage(): bool
    {
        return $this->hasPermission(StaticPagePolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canEditStaticPage(): bool
    {
        return $this->hasPermission(StaticPagePolicy::FEATURE_NAME, BasePolicy::EDIT);
    }

    public function canDeleteStaticPage(): bool
    {
        return $this->hasPermission(StaticPagePolicy::FEATURE_NAME, BasePolicy::DELETE);
    }

    public function canViewAnyMetadata(): bool
    {
        return $this->hasPermission(MetadataPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewMetadata(): bool
    {
        return $this->hasPermission(MetadataPolicy::FEATURE_NAME, BasePolicy::VIEW);
    }

    public function canEditMetadata(): bool
    {
        return $this->hasPermission(MetadataPolicy::FEATURE_NAME, BasePolicy::EDIT);
    }

    public function canViewAnyUsers(): bool
    {
        return $this->hasPermission(UserPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewSuperAdmin(): bool
    {
        return $this->hasPermission(UserPolicy::FEATURE_NAME, UserPolicy::VIEW_SUPERADMIN);
    }

    public function canViewAnyRoles(): bool
    {
        return $this->hasPermission(RolePolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewRole(): bool
    {
        return $this->hasPermission(RolePolicy::FEATURE_NAME, BasePolicy::VIEW);
    }

    public function canManagePermissions(): bool
    {
        return $this->hasPermission(RolePolicy::FEATURE_NAME, RolePolicy::MANAGE);
    }

    public function canViewAnyPermission(): bool
    {
        return $this->hasPermission(PermissionPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewProtectedPermissions(): bool
    {
        return $this->hasPermission(PermissionPolicy::FEATURE_NAME, PermissionPolicy::VIEW_PROTECTED);
    }

    public function canViewSuperAdminRole(): bool
    {
        return $this->hasPermission(RolePolicy::FEATURE_NAME, RolePolicy::VIEW_SUPERADMIN);
    }

    public function canViewAnyAuthor(): bool
    {
        return $this->hasPermission(AuthorPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewAnyArticle(): bool
    {
        return $this->hasPermission(ArticlePolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canViewAnyArticleCategory(): bool
    {
        return $this->hasPermission(ArticleCategoryPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }
}