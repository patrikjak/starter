<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Users;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class PermissionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'permission';

    public const string MANAGE_PROTECTED = 'manageProtected';
}
