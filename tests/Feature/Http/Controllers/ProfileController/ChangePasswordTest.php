<?php

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ProfileController;

use Orchestra\Testbench\Attributes\DefineEnvironment;
use Patrikjak\Starter\Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testChangePasswordCanBeRendered(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('change-password'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.profile.change-password');

        $this->assertMatchesHtmlSnapshot($response->getContent());
    }

    public function testChangePasswordNoAuthenticated(): void
    {
        $this->get(route('change-password'))->assertRedirect(route('login'));
    }

    #[DefineEnvironment('disableProfile')]
    public function testChangePasswordCannotBeRenderedWithoutEnabledFeature()
    {
        $this->actingAs($this->createUser());

        $this->get('profile/change-password')->assertNotFound();
    }
}