<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Enums\Articles;

enum Visibility: string
{
    case PUBLIC = 'public';

    case PRIVATE = 'private';
}
