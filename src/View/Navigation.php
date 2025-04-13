<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Patrikjak\Starter\Dto\Common\NavigationItem;
use Patrikjak\Starter\Models\Users\User;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Navigation extends Component
{
    public function __construct(
        private readonly AuthManager $authManager,
        private readonly Config $config,
        private readonly UrlGenerator $urlGenerator,
        private readonly Request $request,
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

        $currentUser = $this->authManager->user();
        assert($currentUser instanceof User);

        $staticPagesFeature = $this->config->get('pjstarter.features.static_pages');
        $articlesFeature = $this->config->get('pjstarter.features.articles');

        if ($this->config->get('pjstarter.features.dashboard')) {
            $items[] = new NavigationItem(
                __('pjstarter::general.dashboard'),
                route('dashboard'),
            );
        }

        if ($staticPagesFeature && $currentUser->canViewAnyStaticPage()) {
            $items[] = new NavigationItem(
                __('pjstarter::pages.static_pages.title'),
                route('static-pages.index'),
            );
        }

        if ($articlesFeature) {
            $items[] = new NavigationItem(
                __('pjstarter::pages.articles.title'),
                route('articles.index'),
                subItems: [
                    new NavigationItem(
                        __('pjstarter::pages.articles.categories.title'),
                        route('articles.categories.index'),
                    ),
                ],
            );

            $items[] = new NavigationItem(
                __('pjstarter::pages.authors.title'),
                route('authors.index'),
            );
        }

        if (($staticPagesFeature || $articlesFeature) && $currentUser->canViewAnyMetadata()) {
            $items[] = new NavigationItem(
                __('pjstarter::pages.metadata.title'),
                route('metadata.index'),
            );
        }

        if ($this->config->get('pjstarter.features.users')) {
            $usersSubItems = [];

            if ($currentUser->canViewAnyRoles()) {
                $usersSubItems[] = new NavigationItem(
                    __('pjstarter::pages.users.roles.title'),
                    route('users.roles.index'),
                );
            }

            if ($currentUser->canViewAnyPermission()) {
                $usersSubItems[] = new NavigationItem(
                    __('pjstarter::pages.users.permissions.title'),
                    route('users.permissions.index'),
                );
            }

            if ($currentUser->canViewAnyUsers()) {
                $items[] = new NavigationItem(
                    __('pjstarter::pages.users.title'),
                    route('users.index'),
                    subItems: $usersSubItems,
                );
            }
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

            $parentHasSubItems = $this->parentHasSubItems($item);
            $shouldHighlight = $this->shouldHighlightItem($item);

            if ($shouldHighlight) {
                $newClasses[] = 'active';

                if ($parentHasSubItems) {
                    $this->removeActiveClassFromParents($item);
                }
            }

            $item->classes = implode(' ', $newClasses);

            if (count($item->subItems) > 0) {
                $this->setItemClasses($item->subItems);
            }
        }
    }

    private function shouldHighlightItem(NavigationItem $item): bool
    {
        $matchingRoute = $this->getMatchingRoute($item->getUrl());

        $currentPrefix = $this->request->route()?->getPrefix();
        $matchingPagePrefix = $matchingRoute?->getPrefix();

        $currentUrlNotExistsInSubItems = !in_array(
            $this->urlGenerator->current(),
            $this->getItemUrls($item->subItems),
            true,
        );

        $currentRouteIsItemUrl = $this->urlGenerator->current() === $item->getUrl();
        $matchingRoutesPrefixes = $currentPrefix === $matchingPagePrefix && $currentPrefix !== '';

        return $currentRouteIsItemUrl
            || ($matchingRoutesPrefixes && $currentUrlNotExistsInSubItems);
    }

    private function getMatchingRoute(string $url): ?RoutingRoute
    {
        try {
            return Route::getRoutes()->match(Request::create($url));
        } catch (NotFoundHttpException | MethodNotAllowedHttpException) {
            return null;
        }
    }

    /**
     * @param array<NavigationItem> $items
     * @return array<string>
     */
    private function getItemUrls(array $items): array
    {
        $urls = [];

        foreach ($items as $item) {
            $urls[] = $item->getUrl();
        }

        return $urls;
    }

    private function parentHasSubItems(?NavigationItem $item, bool $hasParent = false): bool
    {
        if ($item->getParent() === null) {
            return $hasParent;
        }

        $parent = $item->getParent();

        $this->parentHasSubItems($parent, true);

        return true;
    }

    private function removeActiveClassFromParents(?NavigationItem $item, bool $skipCurrentItem = true): void
    {
        if ($item === null) {
            return;
        }

        if (!$skipCurrentItem) {
            $item->classes = str_replace('active', '', $item->classes);
            $item->classes = trim($item->classes);
        }

        $this->removeActiveClassFromParents($item->getParent(), false);
    }
}
