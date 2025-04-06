<?php

namespace Patrikjak\Starter\Factories;

use Patrikjak\Starter\Exceptions\Common\ModelIsNotInstanceOfBaseModelException;
use Patrikjak\Starter\Models\Users\Permission;

class ModelFactory
{
    /**
     * @throws ModelIsNotInstanceOfBaseModelException
     */
    public static function getPermissionModel(): string
    {
        $configModel = config('pjstarter.models.permission');

        if ($configModel !== Permission::class && !is_subclass_of($configModel, Permission::class)) {
            throw new ModelIsNotInstanceOfBaseModelException($configModel, Permission::class);
        }

        return $configModel;
    }
}