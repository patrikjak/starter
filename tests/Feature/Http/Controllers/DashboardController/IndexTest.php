<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\DashboardController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\Factories\UserFactory;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\Tests\Traits\ConfigSetter;

class IndexTest extends TestCase
{
    use ConfigSetter;

    #[DefineEnvironment('enableAllFeatures')]
    public function testDashboardCanBeRendered(): void
    {
        $this->actingAs(UserFactory::createDefaultUserWithoutEvents());

        $response = $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.dashboard');

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    #[DefineEnvironment('disableContentFeatures')]
    public function testDashboardWithNoStats(): void
    {
        $this->actingAs(UserFactory::createDefaultUserWithoutEvents());

        $response = $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.dashboard');

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testDashboardNoAuthenticated(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
