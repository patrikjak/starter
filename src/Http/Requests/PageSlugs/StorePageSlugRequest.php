<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\PageSlugs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Rules\PageSlugs\EmptySlugExistsRule;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class StorePageSlugRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sluggableType = $this->route('pageSlug');
        assert($sluggableType instanceof PageSlug);

        return $this->user()->can('update', $sluggableType->sluggable_type);
    }
    
    /**
     * @return array<string, array<string>|EmptySlugExistsRule>
     */
    public function rules(): array
    {
        if ($this->input('slug') === '') {
            return ['slug' => new EmptySlugExistsRule()];
        }

        return [
            'slug' => ['required', 'max:191', 'unique:page_slugs,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'slug.max' => trans_choice('pjutils::validation.max.string', 191),
            'slug.unique' => trans_choice('pjutils::validation.unique', GrammaticalGender::MASCULINE),
            'slug.regex' => __('pjstarter::validation.slug'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'slug' => __('pjstarter::pages.static_pages.slug'),
        ];
    }

    public function getSlug(): string
    {
        return Str::slug($this->input('slug'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => trim($this->input('slug', ''), "/ \t\n\r\0\x0B"),
        ]);
    }
}
