<?php

namespace Patrikjak\Starter\Models\Users;

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

    public function canViewAnyPermissions(): bool
    {
        return $this->hasPermission(PermissionPolicy::FEATURE_NAME, BasePolicy::VIEW_ANY);
    }

    public function canManageProtectedPermissions(): bool
    {
        return $this->hasPermission(PermissionPolicy::FEATURE_NAME, PermissionPolicy::MANAGE_PROTECTED);
    }

    public function canViewSuperAdminRole(): bool
    {
        return $this->hasPermission(RolePolicy::FEATURE_NAME, RolePolicy::VIEW_SUPERADMIN);
    }
}