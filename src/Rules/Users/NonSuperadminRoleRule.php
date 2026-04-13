<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Rules\Users;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Patrikjak\Starter\Models\Users\Role;
use Patrikjak\Starter\Models\Users\User;

readonly class NonSuperadminRoleRule implements ValidationRule
{
    public function __construct(private ?User $actingUser)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $role = Role::query()->find($value);

        if (!$role instanceof Role) {
            return;
        }

        if (!$this->actingUser instanceof User) {
            return;
        }

        if ($role->is_superadmin && !$this->actingUser->canViewSuperAdmin()) {
            $fail(__('pjstarter::validation.exists'));
        }
    }
}
