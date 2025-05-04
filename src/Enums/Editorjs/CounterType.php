<?php

namespace Patrikjak\Starter\Enums\Editorjs;

enum CounterType: string
{
    case Numeric = 'numeric';
    case LowerRoman = 'lower-roman';
    case UpperRoman = 'upper-roman';
    case LowerAlpha = 'lower-alpha';
    case UpperAlpha = 'upper-alpha';
}
