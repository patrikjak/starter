<?php

namespace Patrikjak\Starter\Tests\Unit\View\Layout;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Starter\Tests\TestCase;

class AppTest extends TestCase
{
    public function testLayout(): void
    {
        $this->actingAs($this->createUser());

        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjstarter::layout.app />
            HTML
        ));
    }
}