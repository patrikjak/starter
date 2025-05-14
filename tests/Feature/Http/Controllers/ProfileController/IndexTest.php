<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Feature\Http\Controllers\ProfileController;

use Carbon\Carbon;
use Patrikjak\Starter\Tests\TestCase;

class IndexTest extends TestCase
{
    public function testProfileNoAuthenticated(): void
    {
        $this->get(route('admin.profile'))->assertRedirect(route('admin.login'));
    }

    public function testProfileCanBeRendered(): void
    {
        $this->copyIconsToTestSkeleton();

        $this->actingAs($this->createAdminUser());

        $response = $this->get(route('admin.profile'))
            ->assertOk()
            ->assertViewIs('pjstarter::pages.profile.index');

        $this->assertMatchesHtmlSnapshot($response->getContent());

        $this->deleteIconsFromTestSkeleton();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2025, 4, 5));
    }
}