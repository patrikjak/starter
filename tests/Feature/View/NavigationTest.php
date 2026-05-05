<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\View;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Auth\Models\User;
use Patrikjak\Starter\Dto\Common\NavigationGroup;
use Patrikjak\Starter\Dto\Common\NavigationItem;
use Patrikjak\Starter\Enums\BuiltinNavigationGroup;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\Tests\Traits\ConfigSetter;

class NavigationTest extends TestCase
{
    use ConfigSetter;

    #[DefineEnvironment('usesNavigationItems')]
    public function testNavigation(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testNavigationWithEnabledStaticPages(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('disableProfile')]
    public function testNavigationWithDisabledProfile(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('usesNavigationGroups')]
    public function testNavigationWithCustomGroups(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('injectsItemWithoutGroup')]
    public function testNavigationWithItemWithoutGroup(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('injectsItemIntoMainGroup')]
    public function testNavigationWithItemInjectedIntoMainGroup(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('injectsItemIntoContentGroup')]
    public function testNavigationWithItemInjectedIntoContentGroup(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('injectsItemIntoManagementGroup')]
    public function testNavigationWithItemInjectedIntoManagementGroup(): void
    {
        $this->createAndActAsUser();

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    protected function usesNavigationGroups(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => '/dashboard',
            'items' => [
                new NavigationGroup('Custom Group', [
                    new NavigationItem('Custom Item A', 'custom-a-url'),
                    new NavigationItem('Custom Item B', 'custom-b-url'),
                ]),
                new NavigationGroup('Another Group', [
                    new NavigationItem('Another Item', 'another-item-url', icon: '<svg></svg>'),
                ]),
            ],
            'user_items' => [],
        ]);
    }

    protected function usesNavigationItems(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => static function (): string {
                return 'home.url';
            },
            'items' => [
                'feature' => static function (): NavigationItem {
                    return new NavigationItem('Feature', 'feature-url');
                },
                'another-feature' => new NavigationItem('Another Feature', 'another-feature-url'),
                'feature_hidden' => static function (User $user): ?NavigationItem {
                    if ($user->name === self::TESTER_NAME) {
                        return null;
                    }

                    return new NavigationItem('Feature not for tester', 'feature-hidden-url');
                },
                'item_with_subitems' => new NavigationItem(
                    'Item with subitems',
                    'item-with-subitems-url',
                    null,
                    [
                        new NavigationItem('Subitem 1', 'subitem-1-url'),
                        new NavigationItem('Subitem 2', 'subitem-2-url'),
                    ],
                ),
                'more_subitems' => new NavigationItem(
                    'More subitems',
                    'more-subitems-url',
                    null,
                    [
                        new NavigationItem('Subitem 3', 'subitem-3-url', subItems: [
                            new NavigationItem('Subitem 3.1', 'subitem-3-1-url'),
                            new NavigationItem('Subitem 3.2', 'subitem-3-2-url'),
                        ]),
                        new NavigationItem('Subitem 4', 'subitem-4-url'),
                    ],
                ),
            ],
            'user_items' => [
                'user_action' => static function (): NavigationItem {
                    return new NavigationItem('User Action', 'user_action-url');
                },
                'another_user_action' => new NavigationItem(
                    'Another User Action',
                    'another_user_action-url',
                    'custom-class',
                ),
            ],
        ]);
    }

    protected function injectsItemWithoutGroup(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => '/dashboard',
            'items' => [
                new NavigationItem('App Item', 'app-item-url'),
            ],
            'user_items' => [],
        ]);
    }

    protected function injectsItemIntoMainGroup(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => '/dashboard',
            'items' => [
                new NavigationItem('Custom Main Item', 'custom-main-url', group: BuiltinNavigationGroup::Main),
            ],
            'user_items' => [],
        ]);
    }

    protected function injectsItemIntoContentGroup(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => '/dashboard',
            'items' => [
                new NavigationItem('Projekty', 'projekty-url', group: BuiltinNavigationGroup::Content),
            ],
            'user_items' => [],
        ]);
    }

    protected function injectsItemIntoManagementGroup(Application $app): void
    {
        $app['config']->set('pjstarter.navigation', [
            'home' => '/dashboard',
            'items' => [
                new NavigationItem(
                    'Custom Management Item',
                    'custom-mgmt-url',
                    group: BuiltinNavigationGroup::Management,
                ),
            ],
            'user_items' => [],
        ]);
    }
}
