<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\PageSlugs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Patrikjak\Starter\Dto\PageSlugs\UpdatePageSlug;
use Patrikjak\Starter\Models\PageSlugs\PageSlug;
use Patrikjak\Starter\Rules\PageSlugs\EmptySlugExistsRule;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class UpdatePageSlugRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sluggableType = $this->route('pageSlug');
        assert($sluggableType instanceof PageSlug);

        return $this->user()->can('update', $sluggableType->sluggable_type);
    }
    
    /**
     * @return array<string, array<string|EmptySlugExistsRule|Unique>>
     */
    public function rules(): array
    {
        $prefixRules = ['nullable', 'regex:/^[a-z0-9\/]+(?:-[a-z0-9\/]+)*$/'];

        if ($this->input('slug') === '') {
            return [
                'prefix' => $prefixRules,
                'slug' => [new EmptySlugExistsRule()],
            ];
        }

        $currentSlug = $this->route('pageSlug');
        assert($currentSlug instanceof PageSlug);

        return [
            'prefix' => $prefixRules,
            'slug' => [
                'required',
                'max:191',
                Rule::unique('page_slugs', 'slug')->ignore($currentSlug->id),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ],
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
            'prefix.regex' => __('pjstarter::validation.slug_prefix'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'prefix' => __('pjstarter::pages.static_pages.prefix'),
            'slug' => __('pjstarter::pages.static_pages.slug'),
        ];
    }

    public function getUpdatePageSlug(): UpdatePageSlug
    {
        return new UpdatePageSlug(Str::slug($this->input('slug')), $this->input('prefix'));
    }

    protected function prepareForValidation(): void
    {
        $prefix = $this->input('prefix');

        if ($prefix !== null) {
            $prefix = trim($this->input('prefix'), "/ \t\n\r\0\x0B");
        }

        $this->merge([
            'prefix' => $prefix,
            'slug' => trim($this->input('slug', ''), "/ \t\n\r\0\x0B"),
        ]);
    }
}
