<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ProfileController;

use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    public function testProfileNoAuthenticated(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    public function testProfileCanBeRendered(): void
    {
        $this->copyIconsToTestSkeleton();

        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('profile'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.profile.index');

        $this->assertMatchesHtmlSnapshot($response->getContent());

        $this->deleteIconsFromTestSkeleton();
    }
}