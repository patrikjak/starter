<?php

namespace Patrikjak\Starter\Http\Requests\StaticPages;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class StoreStaticPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:191', 'unique:static_pages,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::NEUTER),
            'name.max' => trans_choice('pjutils::validation.max.string', 191),
            'name.unique' => __('pjutils::validation.unique'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.static_pages.name'),
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }
}
