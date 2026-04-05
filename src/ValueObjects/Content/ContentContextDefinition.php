<?php

declare(strict_types=1);

namespace Patrikjak\Starter\ValueObjects\Content;

final readonly class ContentContextDefinition
{
    /**
     * @param class-string $modelClass
     */
    public function __construct(
        public string $directory,
        public string $modelClass,
        public ?string $disk = null,
    ) {
    }
}
