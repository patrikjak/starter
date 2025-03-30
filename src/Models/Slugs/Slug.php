<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Slugs;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Patrikjak\Starter\Database\Factories\Slugs\SlugFactory;

/**
 * @property string $id
 * @property ?string $prefix
 * @property string $slug
 * @property string $sluggable_id
 * @property string $sluggable_type
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Sluggable $sluggable
 */
class Slug extends Model
{
    use HasUuids;
    use HasFactory;

    public function sluggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return array<string, string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    protected static function newFactory(): SlugFactory
    {
        return SlugFactory::new();
    }
}
