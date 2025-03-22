<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Metadata;

use Illuminate\Foundation\Http\FormRequest;

class StoreMetadataRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
