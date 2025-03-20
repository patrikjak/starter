<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\UrlGenerator;
use Illuminate\View\Component;
use Patrikjak\Auth\Models\User;

class Navigation extends Component
{
    public function __construct(
        private readonly AuthManager $authManager,
        private readonly Config $config,
        private readonly UrlGenerator $urlGenerator,
    ) {
    }

    public function render(): View
    {
        $user = $this->authManager->user();
        assert($user instanceof User);

        return view('pjstarter::components.navigation', [
            'appName' => $this->config->get('pjstarter.app_name'),
            'appNameInitials' => $this->getAppNameInitials(),
            'homeUrl' => $this->getAppHome(),
            'userName' => $user->name,
            'userEmail' => $user->email,
            'userNameInitials' => $this->getUserNameInitials(),
            'items' => $this->getItems(),
            'userItems' => $this->getUserItems(),
        ]);
    }

    private function getAppNameInitials(): string
    {
        return strtoupper(substr($this->config->get('pjstarter.app_name'), 0, 2));
    }

    private function getUserNameInitials(): string
    {
        $user = $this->authManager->user();
        assert($user instanceof User);

        $name = $user->name;

        $nameParts = explode(' ', $name);

        if (count($nameParts) < 2) {
            return strtoupper(substr($name, 0, 2));
        }

        $initials = array_map(static fn (string $part): string => $part[0], $nameParts);

        return implode('', $initials);
    }

    /**
     * @return array<NavigationItem>
     */
    private function getItems(): array
    {
        $items = [];

        if ($this->config->get('pjstarter.features.dashboard')) {
            $items[] = new NavigationItem(
                __('pjstarter::general.dashboard'),
                route('dashboard'),
            );
        }

        if ($this->config->get('pjstarter.features.static_pages')) {
            $items[] = new NavigationItem(
                __('pjstarter::pages.static_pages.title'),
                route('static-pages.index'),
            );
        }

        if ($this->config->get('pjstarter.features.metadata')) {
            $items[] = new NavigationItem(
                __('pjstarter::pages.metadata.title'),
                route('metadata.index'),
            );
        }

        $items = array_merge($items, $this->getItemsFromConfig($this->config->get('pjstarter.navigation.items')));
        $this->setItemClasses($items);

        return $items;
    }

    private function getAppHome(): string
    {
        $homeUrl = $this->config->get('pjstarter.navigation.home');

        if ($homeUrl instanceof Closure) {
            return $homeUrl();
        }

        return $homeUrl;
    }

    /**
     * @return array<NavigationItem>
     */
    private function getUserItems(): array
    {
        $items = [];

        if ($this->config->get('pjstarter.features.profile')) {
            $items[] = new NavigationItem(__('pjstarter::pages.profile.title'), route('profile'));
        }

        $items = array_merge($items, $this->getItemsFromConfig($this->config->get('pjstarter.navigation.user_items')));
        $this->setItemClasses($items);

        return $items;
    }

    /**
     * @param array<NavigationItem|Closure> $configItems
     * @return array<NavigationItem>
     */
    private function getItemsFromConfig(array $configItems): array
    {
        $items = [];

        foreach ($configItems as $item) {
            if ($item instanceof NavigationItem) {
                $items[] = $item;

                continue;
            }

            $returnValue = $item($this->authManager->user());

            if ($returnValue instanceof NavigationItem) {
                $items[] = $returnValue;
            }
        }

        return $items;
    }

    /**
     * @param array<NavigationItem> $items
     */
    private function setItemClasses(array $items): void
    {
        foreach ($items as $item) {
            $currentClasses = $item->classes;
            $newClasses = ['item'];

            if ($currentClasses !== null) {
                $newClasses[] = $item->classes;
            }

            if ($this->urlGenerator->current() === $item->getUrl()) {
                $newClasses[] = 'active';
            }

            $item->classes = implode(' ', $newClasses);
        }
    }
}
