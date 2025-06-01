<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Tests\Unit\View\Editorjs;

use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ChecklistItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListElement;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\ListItem;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\OrderedListItemMeta;
use Patrikjak\Starter\Dto\Editorjs\Blocks\List\UnorderedListItemMeta;
use Patrikjak\Starter\Enums\Editorjs\ListStyle;
use Patrikjak\Starter\Tests\TestCase;
use Patrikjak\Starter\View\Editorjs\ListRenderer;

class ListRendererTest extends TestCase
{
    public function testRenderOrderedList(): void
    {
        $listElement = new ListElement(
            id: 'test-id',
            style: ListStyle::Ordered,
            items: [
                new ListItem(
                    content: 'First item',
                    itemMeta: new OrderedListItemMeta(),
                ),
                new ListItem(
                    content: 'Second item',
                    itemMeta: new OrderedListItemMeta(),
                ),
            ]
        );

        $renderer = new ListRenderer($listElement);
        $html = $renderer->render();

        $expected = '<ol class="ordered"><li>First item</li>'.PHP_EOL.'<li>Second item</li></ol>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderUnorderedList(): void
    {
        $listElement = new ListElement(
            id: 'test-id',
            style: ListStyle::Unordered,
            items: [
                new ListItem(
                    content: 'First item',
                    itemMeta: new UnorderedListItemMeta(),
                ),
                new ListItem(
                    content: 'Second item',
                    itemMeta: new UnorderedListItemMeta(),
                ),
            ]
        );

        $renderer = new ListRenderer($listElement);
        $html = $renderer->render();

        $expected = '<ul class="unordered"><li>First item</li>'.PHP_EOL.'<li>Second item</li></ul>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderChecklist(): void
    {
        $listElement = new ListElement(
            id: 'test-id',
            style: ListStyle::Checklist,
            items: [
                new ListItem(
                    content: 'Checked item',
                    itemMeta: new ChecklistItemMeta(checked: true),
                ),
                new ListItem(
                    content: 'Unchecked item',
                    itemMeta: new ChecklistItemMeta(checked: false),
                ),
            ]
        );

        $renderer = new ListRenderer($listElement);
        $html = $renderer->render();

        $expected = '<ul class="checklist">' . 
            '<li><input type="checkbox" checked> Checked item</li>' . PHP_EOL . 
            '<li><input type="checkbox" > Unchecked item</li>' . 
            '</ul>';
        $this->assertEquals($expected, $html);
    }

    public function testRenderNestedList(): void
    {
        $listElement = new ListElement(
            id: 'test-id',
            style: ListStyle::Unordered,
            items: [
                new ListItem(
                    content: 'Parent item',
                    itemMeta: new UnorderedListItemMeta(),
                    items: [
                        new ListItem(
                            content: 'Child item 1',
                            itemMeta: new UnorderedListItemMeta(),
                        ),
                        new ListItem(
                            content: 'Child item 2',
                            itemMeta: new UnorderedListItemMeta(),
                        ),
                    ]
                ),
                new ListItem(
                    content: 'Another parent item',
                    itemMeta: new UnorderedListItemMeta(),
                ),
            ]
        );

        $renderer = new ListRenderer($listElement);
        $html = $renderer->render();

        $expected = '<ul class="unordered">' . 
            '<li>Parent item</li>' . PHP_EOL . 
            '<ul class="nested unordered">' . 
                '<li>Child item 1</li>' . PHP_EOL . 
                '<li>Child item 2</li>' . 
            '</ul>' . PHP_EOL . 
            '<li>Another parent item</li>' . 
            '</ul>';
        $this->assertEquals($expected, $html);
    }
}
