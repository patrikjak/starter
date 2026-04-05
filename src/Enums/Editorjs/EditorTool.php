<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Enums\Editorjs;

enum EditorTool: string
{
    case Header = 'header';
    case List = 'list';
    case Image = 'image';
    case Raw = 'raw';
    case Underline = 'underline';
}
