<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Authors;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class AuthorPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'author';
}
