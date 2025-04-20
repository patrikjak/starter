<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Articles;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class ArticlePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'article';
}
