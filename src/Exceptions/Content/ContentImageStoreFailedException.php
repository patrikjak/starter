<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Content;

use RuntimeException;

final class ContentImageStoreFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Failed to store content image');
    }
}
