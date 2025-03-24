<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Metadata;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Patrikjak\Starter\Observers\Metadata\MetadataObserver;

/**
 * @property string $id
 * @property string $title
 * @property ?string $description
 * @property ?string $keywords
 * @property ?string $canonical_url
 * @property ?string $structured_data
 * @property string $metadatable_id
 * @property string $metadatable_type
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Metadatable $metadatable
 */
#[ObservedBy(MetadataObserver::class)]
class Metadata extends Model
{
    use HasUuids;

    public function metadatable(): MorphTo
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
}
