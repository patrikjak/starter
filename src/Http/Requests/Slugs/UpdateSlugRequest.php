<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Slugs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Patrikjak\Starter\Dto\Slugs\UpdateSlug;
use Patrikjak\Starter\Models\Slugs\Slug;
use Patrikjak\Starter\Policies\BasePolicy;
use Patrikjak\Starter\Rules\Slugs\EmptySlugExistsRule;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;
use Patrikjak\Utils\Common\Http\Requests\Traits\ValidationException;

class UpdateSlugRequest extends FormRequest
{
    use ValidationException;

    public function authorize(): bool
    {
        $sluggableType = $this->route('slug');
        assert($sluggableType instanceof Slug);

        return $this->user()->can(BasePolicy::EDIT, $sluggableType->sluggable_type);
    }
    
    /**
     * @return array<string, array<string|EmptySlugExistsRule|Unique>>
     */
    public function rules(): array
    {
        $prefixRules = ['nullable', 'regex:/^[a-z0-9\/]+(?:-[a-z0-9\/]+)*$/'];

        $currentSlug = $this->route('slug');
        assert($currentSlug instanceof Slug);

        if ($this->getInputSlug() === null || $this->getInputSlug() === '') {
            return [
                'prefix' => $prefixRules,
                'slug' => [new EmptySlugExistsRule()
                    ->setPrefix($this->getInputPrefix())
                    ->setIgnoredId($currentSlug->id),
                ],
            ];
        }

        return [
            'prefix' => $prefixRules,
            'slug' => [
                'required',
                'max:191',
                Rule::unique('slugs', 'slug')->ignore($currentSlug->id)->where(
                    fn ($query) => $query->where('prefix', $this->getInputPrefix())
                ),
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

    public function getUpdateSlug(): UpdateSlug
    {
        return new UpdateSlug(Str::slug($this->input('slug')), $this->input('prefix'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'prefix' => $this->getInputPrefix(),
            'slug' => $this->getInputSlug(),
        ]);
    }

    private function getInputPrefix(): ?string
    {
        $prefix = $this->input('prefix');

        if ($prefix === null) {
            return null;
        }

        return trim($prefix, "/ \t\n\r\0\x0B");
    }

    private function getInputSlug(): ?string
    {
        $slug = $this->input('slug');

        if ($slug === null) {
            return null;
        }

        return trim($slug, "/ \t\n\r\0\x0B");
    }
}
