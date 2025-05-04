<?php

namespace Patrikjak\Starter\Enums\Editorjs;

enum BlockType: string
{
    case Paragraph = 'paragraph';
    case Header = 'header';
    case List = 'list';
    case Raw = 'raw';
    case Image = 'image';
}
