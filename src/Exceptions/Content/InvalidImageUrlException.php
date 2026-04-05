<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Exceptions\Content;

use RuntimeException;

final class InvalidImageUrlException extends RuntimeException
{
    public function __construct(string $url)
    {
        parent::__construct(sprintf("Image URL '%s' is not valid. Only HTTPS URLs are accepted.", $url));
    }
}
