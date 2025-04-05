<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Common;

use Closure;

class NavigationItem
{
    private ?self $parent = null;

    /**
     * @param array<NavigationItem> $subItems
     */
    public function __construct(
        public string $label,
        public string|Closure $url,
        public ?string $classes = null,
        public array $subItems = [],
    ) {
        foreach ($this->subItems as $subItem) {
            $subItem->setParent($this);
        }
    }

    public function getUrl(): string
    {
        if ($this->url instanceof Closure) {
            return call_user_func($this->url);
        }

        return $this->url;
    }

    public function setParent(self $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }
}