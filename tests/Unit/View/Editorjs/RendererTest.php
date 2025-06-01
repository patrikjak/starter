<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Header\Header;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Paragraph\Paragraph;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\Renderer;

class RendererTest extends TestCase
{
    public function testRenderEmptyEditorData(): void
    {
        $editorData = new EditorData(
            time: Carbon::now(),
            blocks: new Collection([]),
            version: '2.22.2',
            rawData: '{"time": "2023-01-01", "blocks": [], "version": "2.22.2"}'
        );

        $renderer = new Renderer($editorData);
        $html = $renderer->render();

        $this->assertEquals('', $html);
    }

    public function testRenderSingleBlock(): void
    {
        $paragraph = new Paragraph(
            id: 'test-paragraph',
            text: 'This is a test paragraph'
        );

        $editorData = new EditorData(
            time: Carbon::now(),
            blocks: new Collection([$paragraph]),
            version: '2.22.2',
            rawData: '{"time": "2023-01-01", "blocks": [' . 
                '{"id": "test-paragraph", "type": "paragraph", "data": {"text": "This is a test paragraph"}}' . 
                '], "version": "2.22.2"}'
        );

        $renderer = new Renderer($editorData);
        $html = $renderer->render();

        $expected = '<p>This is a test paragraph</p>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderMultipleBlocks(): void
    {
        $header = new Header(
            id: 'test-header',
            text: 'Test Header',
            level: 2
        );

        $paragraph1 = new Paragraph(
            id: 'test-paragraph-1',
            text: 'First paragraph'
        );

        $paragraph2 = new Paragraph(
            id: 'test-paragraph-2',
            text: 'Second paragraph'
        );

        $editorData = new EditorData(
            time: Carbon::now(),
            blocks: new Collection([$header, $paragraph1, $paragraph2]),
            version: '2.22.2',
            rawData: '{"time": "2023-01-01", "blocks": [...], "version": "2.22.2"}'
        );

        $renderer = new Renderer($editorData);
        $html = $renderer->render();

        $expected = '<h2>Test Header</h2>' . PHP_EOL . '<p>First paragraph</p>' . PHP_EOL . '<p>Second paragraph</p>';
        $this->assertEquals($expected, $html);
    }
}
