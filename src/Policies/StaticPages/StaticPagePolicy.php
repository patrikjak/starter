<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\StaticPages;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Auth\Models\RoleType;
use Patrikjak\Auth\Models\User;
use Patrikjak\Starter\Models\StaticPages\StaticPage;

class StaticPagePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasRole(RoleType::SUPERADMIN);
    }

    public function update(User $user): bool
    {
        return $this->create($user);
    }

    public static function can(string $action): string
    {
        return sprintf('can:%s,%s', $action, StaticPage::class);
    }
}
