<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\DashboardController;

use Patrikjak\Starter\Tests\Factories\UserFactory;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    public function testDashboardCanBeRendered(): void
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