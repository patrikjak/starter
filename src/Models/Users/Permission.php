<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Patrikjak\Starter\Casts\TranslatableCast;

/**
 * @property string $name
 * @property string $description
 * @property bool $protected
 */
class Permission extends Model
{
    use PermissionsDefinition;

    public function allDescriptions(): array
    {
        return json_decode($this->getRawOriginal('description'), true) ?? [];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    protected function casts(): array
    {
        return [
            'description' => TranslatableCast::class,
            'protected' => 'bool',
        ];
    }
}
