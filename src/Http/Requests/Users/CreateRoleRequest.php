<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class CreateRoleRequest extends FormRequest
{
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:roles,slug', 'regex:/^[a-z0-9-]+$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::FEMININE),
            'name.max' => __('pjutils::validation.max.string'),
            'slug.unique' => __('pjutils::validation.unique'),
            'slug.regex' => __('pjstarter::validation.slug'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.users.roles.name'),
            'slug' => __('pjstarter::pages.users.roles.slug'),
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getSlug(): string
    {
        $slug = $this->input('slug');

        return $slug !== null && $slug !== '' ? $slug : Str::slug($this->input('name'));
    }
}
