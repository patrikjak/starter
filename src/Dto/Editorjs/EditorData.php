<?php

declare(strict_types = 1);

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

    public function toJson(): string
    {
        return $this->rawData;
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}