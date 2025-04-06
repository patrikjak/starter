<?php

namespace Patrikjak\Starter\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Casts\TranslatableCast;

/**
 * @property string $name
 * @property string $description
 * @property bool $protected
 */
class Permission extends Model
{
    use PermissionsData;

    protected function casts(): array
    {
        return [
            'description' => TranslatableCast::class,
            'protected' => 'bool',
        ];
    }

    public function allDescriptions(): array
    {
        return json_decode($this->getRawOriginal('description'), true) ?? [];
    }
}
