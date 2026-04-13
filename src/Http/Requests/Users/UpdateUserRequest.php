<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Starter\Rules\Users\NonSuperadminRoleRule;

class UpdateUserRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'string',
                'exists:roles,id',
                new NonSuperadminRoleRule($this->user()),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role_id.exists' => __('pjstarter::validation.exists'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'role_id' => __('pjstarter::pages.users.role'),
        ];
    }

    public function getRoleId(): string
    {
        return $this->input('role_id');
    }
}
