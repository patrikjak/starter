<?php

namespace Patrikjak\Starter\Http\Requests\Metadata;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Starter\Dto\PageSlugs\CreatePageSlug;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class StorePageSlugRequest extends FormRequest
{
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:191'],
            'slug' => ['required', 'max:191', 'unique:page_slugs,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'name.max' => trans_choice('pjutils::validation.max.string', 191),
            'slug.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'slug.max' => trans_choice('pjutils::validation.max.string', 191),
            'slug.unique' => trans_choice('pjutils::validation.unique', GrammaticalGender::MASCULINE),
            'slug.regex' => __('pjstarter::validation.slug'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.metadata.pages.name'),
            'slug' => __('pjstarter::pages.metadata.pages.slug'),
        ];
    }

    public function getPage(): CreatePageSlug
    {
        return new CreatePageSlug($this->input('name'), $this->input('slug'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => trim($this->input('slug'), "/ \t\n\r\0\x0B"),
        ]);
    }
}
