<?php

declare(strict_types=1);

namespace Patrikjak\Starter\View;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\UrlGenerator;
use Illuminate\View\Component;
use Patrikjak\Starter\Dto\Common\NavigationGroup;
use Patrikjak\Starter\Dto\Common\NavigationItem;
use Patrikjak\Starter\Models\Users\User;

class Navigation extends Component
{
    public const string ICON_DASHBOARD = 'heroicon-o-home';
    public const string ICON_STATIC_PAGES = 'heroicon-o-document-text';
    public const string ICON_ARTICLES = 'heroicon-o-newspaper';
    public const string ICON_AUTHORS = 'heroicon-o-user';
    public const string ICON_METADATA = 'heroicon-o-tag';
    public const string ICON_USERS = 'heroicon-o-users';

    public function __construct(
        private readonly AuthManager $authManager,
        private readonly Config $config,
        private readonly UrlGenerator $urlGenerator,
    ) {
    }

    public function render(): View
    {
        $user = $this->getUser();

        return view('pjstarter::components.navigation', [
            'appName' => $this->config->get('pjstarter.app_name'),
            'appNameInitials' => $this->getAppNameInitials(),
            'homeUrl' => $this->getAppHome(),
            'userName' => $user?->name,
            'userEmail' => $user?->email,
            'userNameInitials' => $this->getUserNameInitials(),
            'groups' => $this->getGroups(),
            'userItems' => $this->getUserItems(),
            'authFeaturesEnabled' => $this->config->get('pjstarter.features.auth'),
        ]);
    }

    private function getAppNameInitials(): string
    {
        return strtoupper(substr($this->config->get('pjstarter.app_name'), 0, 2));
    }

    private function getUserNameInitials(): string
    {
        $user = $this->getUser();

        if ($user === null) {
            return '';
        }

        $name = $user->name;

        $nameParts = explode(' ', $name);

        if (count($nameParts) < 2) {
            return strtoupper(substr($name, 0, 2));
        }

        $initials = array_map(static fn (string $part): string => $part[0], $nameParts);

        return implode('', $initials);
    }

    /**
     * @return array<NavigationGroup>
     */
    private function getGroups(): array
    {
        $currentUser = $this->getUser();

        $staticPagesFeature = $this->config->get('pjstarter.features.static_pages');
        $articlesFeature = $this->config->get('pjstarter.features.articles');

        $mainItems = [];
        $contentItems = [];
        $managementItems = [];

        if ($this->config->get('pjstarter.features.dashboard')) {
            $mainItems[] = new NavigationItem(
                __('pjstarter::general.dashboard'),
                route('admin.dashboard'),
                icon: self::icon(self::ICON_DASHBOARD),
            );
        }

        if ($staticPagesFeature && ($currentUser === null || $currentUser->canViewAnyStaticPage())) {
            $contentItems[] = new NavigationItem(
                __('pjstarter::pages.static_pages.title'),
                route('admin.static-pages.index'),
                icon: self::icon(self::ICON_STATIC_PAGES),
            );
        }

        if ($articlesFeature) {
            $articlesSubItems = [];

            if ($currentUser === null || $currentUser->canViewAnyArticleCategory()) {
                $articlesSubItems[] = new NavigationItem(
                    __('pjstarter::pages.articles.categories.title'),
                    route('admin.articles.categories.index'),
                    subItems: [
                        new NavigationItem(
                            __('pjstarter::pages.articles.categories.index.title'),
                            route('admin.articles.categories.index'),
                        ),
                    ]
                );
            }

            if ($currentUser === null || $currentUser->canViewAnyArticle()) {
                array_unshift($articlesSubItems, new NavigationItem(
                    __('pjstarter::pages.articles.index.title'),
                    route('admin.articles.index'),
                ));

                $contentItems[] = new NavigationItem(
                    __('pjstarter::pages.articles.title'),
                    route('admin.articles.index'),
                    subItems: $articlesSubItems,
                    icon: self::icon(self::ICON_ARTICLES),
                );
            }

            if ($currentUser === null || $currentUser->canViewAnyAuthor()) {
                $contentItems[] = new NavigationItem(
                    __('pjstarter::pages.authors.title'),
                    route('admin.authors.index'),
                    icon: self::icon(self::ICON_AUTHORS),
                );
            }
        }

        if (
            ($staticPagesFeature || $articlesFeature)
            && ($currentUser === null || $currentUser->canViewAnyMetadata())
        ) {
            $contentItems[] = new NavigationItem(
                __('pjstarter::pages.metadata.title'),
                route('admin.metadata.index'),
                icon: self::icon(self::ICON_METADATA),
            );
        }

        if ($this->config->get('pjstarter.features.users')) {
            $usersSubItems = [];

            $canViewUsers = $currentUser === null || $currentUser->canViewAnyUsers();
            $canViewRoles = $currentUser === null || $currentUser->canViewAnyRoles();

            if ($canViewRoles) {
                $usersSubItems[] = new NavigationItem(
                    __('pjstarter::pages.users.roles.title'),
                    route('admin.users.roles.index'),
                );
            }

            if ($canViewUsers) {
                array_unshift($usersSubItems, new NavigationItem(
                    __('pjstarter::pages.users.index.title'),
                    route('admin.users.index'),
                ));
            }

            if ($canViewUsers || $canViewRoles) {
                $defaultUrl = $canViewUsers
                    ? route('admin.users.index')
                    : route('admin.users.roles.index');

                $managementItems[] = new NavigationItem(
                    __('pjstarter::pages.users.title'),
                    $defaultUrl,
                    subItems: $usersSubItems,
                    icon: self::icon(self::ICON_USERS),
                );
            }
        }

        $parsed = $this->getItemsFromConfig($this->config->get('pjstarter.navigation.items'));
        $configItems = $parsed['items'];
        $configGroups = $parsed['groups'];

        $allItems = array_merge($mainItems, $contentItems, $managementItems, $configItems);

        foreach ($configGroups as $group) {
            $allItems = array_merge($allItems, $group->items);
        }

        $this->setItemClasses($allItems);

        $groups = [];

        if ($mainItems !== []) {
            $groups[] = new NavigationGroup(__('pjstarter::general.nav_main'), $mainItems);
        }

        if ($contentItems !== []) {
            $groups[] = new NavigationGroup(__('pjstarter::general.nav_content'), $contentItems);
        }

        if ($managementItems !== [] || $configItems !== []) {
            $groups[] = new NavigationGroup(
                __('pjstarter::general.nav_management'),
                array_merge($managementItems, $configItems),
            );
        }

        foreach ($configGroups as $group) {
            $groups[] = $group;
        }

        return $groups;
    }

    private function getUser(): ?User
    {
        $user = $this->authManager->user();
        assert($user instanceof User || $user === null);

        return $user;
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

        if ($this->config->get('pjstarter.features.profile') && $this->config->get('pjstarter.features.auth')) {
            $items[] = new NavigationItem(__('pjstarter::pages.profile.title'), route('admin.profile'));
        }

        $parsed = $this->getItemsFromConfig($this->config->get('pjstarter.navigation.user_items'));
        $items = array_merge($items, $parsed['items']);
        $this->setItemClasses($items);

        return $items;
    }

    /**
     * @param array<NavigationItem|NavigationGroup|Closure> $configItems
     * @return array{items: array<NavigationItem>, groups: array<NavigationGroup>}
     */
    private function getItemsFromConfig(array $configItems): array
    {
        $items = [];
        $groups = [];

        foreach ($configItems as $item) {
            if ($item instanceof NavigationGroup) {
                $groups[] = $item;

                continue;
            }

            if ($item instanceof NavigationItem) {
                $items[] = $item;

                continue;
            }

            $returnValue = $item($this->getUser());

            if ($returnValue instanceof NavigationItem) {
                $items[] = $returnValue;
            }
        }

        return ['items' => $items, 'groups' => $groups];
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
        $currentUrl = $this->urlGenerator->current();
        $itemUrl = $item->getUrl();

        if ($currentUrl === $itemUrl) {
            return true;
        }

        if ($item->subItems === []) {
            return false;
        }

        $currentUrlNotExistsInSubItems = !in_array(
            $currentUrl,
            $this->getItemUrls($item->subItems),
            true,
        );

        return str_starts_with($currentUrl, rtrim($itemUrl, '/') . '/')
            && $currentUrlNotExistsInSubItems;
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

    public static function icon(string $name): string
    {
        return svg($name)->toHtml();
    }
}
