<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks;

class Paragraph implements Block
{
    public function __construct(public string $text)
    {
    }
}