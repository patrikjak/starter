<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Models\Articles;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasUuids;
}
