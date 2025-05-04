<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Enums\Editorjs;

enum ListStyle: string
{
    case Ordered = 'ordered';
    case Unordered = 'unordered';
    case Checklist = 'checklist';
}
