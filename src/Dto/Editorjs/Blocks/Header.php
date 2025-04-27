<?php

namespace Patrikjak\Starter\Dto\Editorjs\Blocks;

class Header implements Block
{
    public function __construct(public string $text, public int $level)
    {
    }
}