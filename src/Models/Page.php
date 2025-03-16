<?php

namespace Patrikjak\Starter\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 */
class Page extends Model
{
    public $timestamps = false;
}
