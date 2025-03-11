<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Integration\Http\Controllers;

use Patrikjak\Starter\Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    public function testProfileNoAuthenticated(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    public function testChangePasswordCanBeRendered(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('change-password'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.profile.change-password');

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }
}