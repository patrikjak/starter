<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Metadata;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Metadatable
{
    public function getMetaTitle(): string;

    public function getCanonicalUrl(): ?string;

    public function getMetadatableId(): string;

    public function metadata(): MorphOne;

    public function getMetadata(): ?Metadata;

    public function getMetadatableTypeLabel(): string;

    public function getPageName(): string;
}