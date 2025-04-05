<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\View;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Auth\Models\User;
use Patrikjak\Starter\Dto\Common\NavigationItem;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\Tests\Traits\ConfigSetter;

class NavigationTest extends TestCase
{
    use ConfigSetter;

    #[DefineEnvironment('usesNavigationItems')]
    public function testNavigation(): void
    {
        $this->actingAs($this->createUser());

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('enableStaticPages')]
    public function testNavigationWithEnabledStaticPages(): void
    {
        $this->actingAs($this->createUser());

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
    }

    #[DefineEnvironment('disableProfile')]
    public function testNavigationWithDisabledProfile(): void
    {
        $this->actingAs($this->createUser());

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::navigation />
            HTML
        ));
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
}