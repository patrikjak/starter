<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Authors;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Patrikjak\Starter\Models\Articles\Article;

/**
 * @property string $id
 * @property string $name
 * @property ?string $profile_picture
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Author extends Model
{
    use HasUuids;

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
