<?php

namespace Patrikjak\Starter\Models\Metadata;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 */
class Page extends Model
{
    use HasUuids;

    public $timestamps = false;
}
