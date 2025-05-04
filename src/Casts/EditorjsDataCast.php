<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Factories\Editorjs\EditorDataFactory;

class EditorjsDataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): EditorData
    {
        return EditorDataFactory::createFromOutputData(json_decode($value, true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof EditorData) {
            return $value->toJson();
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
