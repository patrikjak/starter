<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Layout;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class AppTest extends TestCase
{
    public function testLayout(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.app />
            HTML
        ));
    }

    public function testLayoutWithTitle(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.app title="Page title" />
            HTML
        ));
    }

    public function testLayoutWithActions(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.app title="Page title">
                    
                    <x-slot:actions>
                        <x-pjstarter::layout.action>
                            <a href="">Action 1</a>
                        </x-pjstarter::layout.action>
                        <x-pjstarter::layout.action>
                            <a href="">Action 2</a>
                        </x-pjstarter::layout.action>
                    </x-slot:actions>
                
                </x-pjstarter::layout.app>
            HTML
        ));
    }

    #[DefineEnvironment('usesDifferentAppName')]
    public function testLayoutWithDifferentName(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.app />
            HTML
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createUser());
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param Application $app
     */
    protected function usesDifferentAppName($app): void
    {
        $app['config']->set('pjstarter.app_name', 'Different App Name');
    }
}