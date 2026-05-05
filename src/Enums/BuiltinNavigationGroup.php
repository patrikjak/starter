<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Enums;

enum BuiltinNavigationGroup: string
{
    case Main = 'main';

    case Content = 'content';

    case Management = 'management';
}
