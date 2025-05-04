<?php

namespace Patrikjak\Starter\Dto\Editorjs;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Dto\Editorjs\Blocks\Block;

class EditorData
{
    /**
     * @param Collection<Block> $blocks
     */
    public function __construct(
        public CarbonInterface $time,
        public Collection $blocks,
        public string $version,
        public string $rawData,
    ) {
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function toJson(): string
    {
        return json_encode([
            'time' => $this->time->timestamp,
            'blocks' => $this->blocks->map(fn (Block $block) => $block->toArray())->all(),
            'version' => $this->version,
        ]);
    }
}