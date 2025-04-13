<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Users;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'user';

    public const string VIEW_SUPERADMIN = 'viewSuperadmin';
}
