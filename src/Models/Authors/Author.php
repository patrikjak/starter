<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Authors;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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
}
