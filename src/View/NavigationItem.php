<?php

namespace Patrikjak\Starter\View;

use Closure;

class NavigationItem
{
    public function __construct(public string $label, public string|Closure $url, public ?string $classes = null)
    {
    }

    public function getUrl(): string
    {
        if ($this->url instanceof Closure) {
            return call_user_func($this->url);
        }

        return $this->url;
    }
}