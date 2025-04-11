<?php

namespace Patrikjak\Starter\Models\Users;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Patrikjak\Auth\Models\Role as BaseRole;

class Role extends BaseRole
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}