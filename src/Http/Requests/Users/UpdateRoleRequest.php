<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class UpdateRoleRequest extends FormRequest
{
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'name.max' => __('pjutils::validation.max.string'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.users.roles.name'),
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }
}
