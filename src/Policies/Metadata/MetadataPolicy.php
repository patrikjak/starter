<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Policies\Metadata;

use Illuminate\Auth\Access\HandlesAuthorization;
use Patrikjak\Starter\Policies\BasePolicy;

class MetadataPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public const string FEATURE_NAME = 'metadata';
}
