<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Raw\Raw;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\RawRenderer;

class RawRendererTest extends TestCase
{
    public function testRenderRawHtml(): void
    {
        $raw = new Raw(
            id: 'test-id',
            html: '<div class="custom-block">Custom HTML content</div>'
        );

        $renderer = new RawRenderer($raw);
        $html = $renderer->render();

        $expected = '<div class="custom-block">Custom HTML content</div>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderComplexRawHtml(): void
    {
        $rawHtml = '<section class="special-section">
            <h2>Special Section Title</h2>
            <p>This is a special section with custom HTML.</p>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
            </ul>
        </section>';

        $raw = new Raw(
            id: 'test-id',
            html: $rawHtml
        );

        $renderer = new RawRenderer($raw);
        $html = $renderer->render();

        $this->assertEquals($rawHtml, $html);
    }
}
