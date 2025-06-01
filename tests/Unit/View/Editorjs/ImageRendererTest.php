<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\Image\Image;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\ImageRenderer;

class ImageRendererTest extends TestCase
{
    public function testRenderBasicImage(): void
    {
        $image = new Image(
            id: 'test-id',
            type: 'image',
            url: 'https://example.com/image.jpg'
        );

        $renderer = new ImageRenderer($image);
        $html = $renderer->render();

        $expected = '<img src="https://example.com/image.jpg" alt="image.jpg">';
        $this->assertEquals($expected, $html);
    }

    public function testRenderImageWithCaption(): void
    {
        $image = new Image(
            id: 'test-id',
            type: 'image',
            url: 'https://example.com/image.jpg',
            caption: 'This is an image caption'
        );

        $renderer = new ImageRenderer($image);
        $html = $renderer->render();

        $expected = '<figure>' . 
            '<img src="https://example.com/image.jpg" alt="image.jpg">' . 
            PHP_EOL . 
            '<figcaption>This is an image caption</figcaption>' . 
            '</figure>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderImageWithBorder(): void
    {
        $image = new Image(
            id: 'test-id',
            type: 'image',
            url: 'https://example.com/image.jpg',
            withBorder: true
        );

        $renderer = new ImageRenderer($image);
        $html = $renderer->render();

        $expected = '<img src="https://example.com/image.jpg" alt="image.jpg" class="with-border">';
        $this->assertEquals($expected, $html);
    }

    public function testRenderImageWithMultipleStyles(): void
    {
        $image = new Image(
            id: 'test-id',
            type: 'image',
            url: 'https://example.com/image.jpg',
            caption: 'Image with all styles',
            withBorder: true,
            withBackground: true,
            stretched: true
        );

        $renderer = new ImageRenderer($image);
        $html = $renderer->render();

        $expected = '<figure>' . 
            '<img src="https://example.com/image.jpg" alt="image.jpg" ' . 
            'class="with-border with-background stretched">' . 
            PHP_EOL . 
            '<figcaption>Image with all styles</figcaption>' . 
            '</figure>';
        $this->assertEquals($expected, $html);
    }
}
