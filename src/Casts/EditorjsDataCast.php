<?php

namespace Patrikjak\Starter\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Patrikjak\Starter\Dto\Editorjs\EditorData;
use Patrikjak\Starter\Factories\Editorjs\EditorDataFactory;

class EditorjsDataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): EditorData
    {
        return EditorDataFactory::createFromOutputData($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (json_validate($value)) {
            return $value;
        }

        return $value;
    }
}
