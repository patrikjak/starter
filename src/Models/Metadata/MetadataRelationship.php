<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Metadata;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Patrikjak\Starter\Models\Slugs\Slug;

trait MetadataRelationship
{
    public function metadata(): MorphOne
    {
        return $this->morphOne(Metadata::class, 'metadatable');
    }
}