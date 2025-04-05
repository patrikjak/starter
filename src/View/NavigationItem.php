<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Starter\Dto\Common\NavigationItem as NavigationItemDto;

class NavigationItem extends Component
{
    public function __construct(public NavigationItemDto $item)
    {
    }

    public function render(): View
    {
        return $this->view('pjstarter::components.navigation-item');
    }

    public function hasActiveSubItem(): bool
    {
        return array_any(
            $this->item->subItems,
            function ($subItem) {
                if (!$subItem instanceof NavigationItemDto) {
                    return false;
                }

                return $this->isActive($subItem);
            }
        );
    }

    private function isActive(NavigationItemDto $item): bool
    {
        if (str_contains($item->classes, 'active')) {
            return true;
        }

        if (count($item->subItems) > 0) {
            if (array_any(
                $item->subItems,
                fn ($subItem) => $subItem instanceof NavigationItemDto && $this->isActive($subItem),
            )) {
                return true;
            }
        }

        return false;
    }
}
