<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph\Paragraph;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\ParagraphRenderer;

class ParagraphRendererTest extends TestCase
{
    public function testRenderParagraph(): void
    {
        $paragraph = new Paragraph(
            id: 'test-id',
            text: 'Test paragraph text'
        );

        $renderer = new ParagraphRenderer($paragraph);
        $html = $renderer->render();

        $expected = '<p>Test paragraph text</p>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderParagraphWithHtmlContent(): void
    {
        $paragraph = new Paragraph(
            id: 'test-id',
            text: 'Text with <strong>bold</strong> and <em>italic</em> formatting'
        );

        $renderer = new ParagraphRenderer($paragraph);
        $html = $renderer->render();

        $expected = '<p>Text with <strong>bold</strong> and <em>italic</em> formatting</p>';
        $this->assertEquals($expected, $html);
    }
}
