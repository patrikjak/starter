<?php

namespace Patrikjak\Starter\Models\PageSlugs;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $slug
 * @property string $sluggable_id
 * @property string $sluggable_type
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class PageSlug extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
