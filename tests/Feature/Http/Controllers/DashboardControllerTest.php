<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers;

use Patrikjak\Starter\Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function testDashboardCanBeRendered(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.dashboard');

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testDashboardNoAuthenticated(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }
}