<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Users;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Patrikjak\Auth\Models\Role as BaseRole;

/**
 * @property-read Collection<Permission> $permissions
 */
class Role extends BaseRole
{
    /**
     * @var list<string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $with = ['permissions'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}