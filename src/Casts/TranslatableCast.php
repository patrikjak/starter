<?php

namespace Patrikjak\Starter\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

readonly class TranslatableCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        $valueIsJson = json_validate($value);

        if (!$valueIsJson) {
            return $value;
        }

        $currentLocale = app()->getLocale();
        $fallbackLocale = app()->getFallbackLocale();

        $allTranslations = json_decode($value, true);

        if (array_key_exists($currentLocale, $allTranslations)) {
            return $allTranslations[$currentLocale];
        }

        if (array_key_exists($fallbackLocale, $allTranslations)) {
            return $allTranslations[$fallbackLocale];
        }

        return reset($allTranslations);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if (is_string($value)) {
            return json_encode([app()->getLocale() => $value]);
        }

        return $value;
    }
}
