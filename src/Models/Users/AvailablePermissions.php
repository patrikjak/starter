<?php

namespace Patrikjak\Starter\Models\Users;

use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Policies\StaticPages\StaticPagePolicy;

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
}