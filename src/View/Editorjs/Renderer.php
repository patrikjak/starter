<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\View\Editorjs;

use Illuminate\View\Component;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Enums\Editorjs\BlockType;

class Renderer extends Component
{
    public function __construct(protected readonly EditorData $data)
    {
    }

    public function render(): string
    {
        $renderedBlocks = [];

        foreach ($this->data->blocks as $block) {
            $renderer = $this->getRendererByBlockType($block);
            $renderedBlocks[] = $renderer->render();
        }

        return implode(PHP_EOL, $renderedBlocks);
    }

    private function getRendererByBlockType(Block $block): BlockRenderer
    {
        return match ($block->getType()) {
            BlockType::Header => new HeaderRenderer($block),
            BlockType::Paragraph => new ParagraphRenderer($block),
            BlockType::List => new ListRenderer($block),
            BlockType::Raw => new RawRenderer($block),
            BlockType::Image => new ImageRenderer($block),
        };
    }
}
