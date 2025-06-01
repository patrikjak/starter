<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Header\Header;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\HeaderRenderer;

class HeaderRendererTest extends TestCase
{
    public function testRenderHeader(): void
    {
        $header = new Header(
            id: 'test-id',
            text: 'Test Header',
            level: 1
        );

        $renderer = new HeaderRenderer($header);
        $html = $renderer->render();

        $expected = '<h1>Test Header</h1>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderHeaderWithDifferentLevel(): void
    {
        $header = new Header(
            id: 'test-id',
            text: 'Test Header',
            level: 3
        );

        $renderer = new HeaderRenderer($header);
        $html = $renderer->render();

        $expected = '<h3>Test Header</h3>';
        $this->assertEquals($expected, $html);
    }
}
