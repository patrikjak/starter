<?php

namespace Patrikjak\Starter\Models\StaticPages;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlugRelationship;
use Patrikjak\Starter\Models\PageSlugs\Sluggable;
use Patrikjak\Starter\Observers\PageSlugs\PageSlugObserver;

/**
 * @property string $id
 * @property string $name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property PageSlug $slug
 */
#[ObservedBy(PageSlugObserver::class)]
class StaticPage extends Model implements Sluggable
{
    use HasUuids;
    use PageSlugRelationship;

    protected $with = ['slug'];

    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function getNewSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getSluggableId(): string
    {
        return $this->id;
    }

    public function getSlug(): PageSlug
    {
        return $this->slug;
    }
}
