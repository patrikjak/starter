<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Users;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Patrikjak\Auth\Factories\RoleFactory;
use Patrikjak\Auth\Models\Role as AuthRole;
use Patrikjak\Starter\Casts\TranslatableCast;

/**
 * @property string $name
 * @property string $description
 * @property bool $protected
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Permission extends Model
{
    use PermissionsDefinition;

    /**
     * @return array<string, string>
     */
    public function allDescriptions(): array
    {
        return json_decode($this->getRawOriginal('description'), true) ?? [];
    }

    public function roles(): BelongsToMany
    {
        $model = RoleFactory::getRoleModelClass();

        if ($model === AuthRole::class) {
            $model = Role::class;
        }

        return $this->belongsToMany($model);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'description' => TranslatableCast::class,
            'protected' => 'bool',
        ];
    }
}
