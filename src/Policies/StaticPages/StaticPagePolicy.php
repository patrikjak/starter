<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\StaticPages;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class StaticPagePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'static_pages';
}
