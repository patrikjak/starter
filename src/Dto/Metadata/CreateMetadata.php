<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Dto\Metadata;

final readonly class CreateMetadata
{
    public function __construct(
        public string $title,
        public string $metadatableId,
        public string $metadatableType,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $canonicalUrl = null,
        public ?string $structuredData = null,
    ) {
    }
}