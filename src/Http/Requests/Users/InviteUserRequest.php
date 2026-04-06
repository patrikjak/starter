<?php

declare(strict_types=1);

namespace Patrikjak\Starter\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class InviteUserRequest extends FormRequest
{
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'role_id' => ['required', 'string', 'exists:roles,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => trans_choice('pjutils::validation.required', GrammaticalGender::FEMININE),
            'email.email' => __('pjstarter::validation.email'),
            'email.unique' => __('pjutils::validation.unique'),
            'role_id.exists' => __('pjstarter::validation.exists'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => __('pjstarter::pages.users.email'),
            'role_id' => __('pjstarter::pages.users.role'),
        ];
    }

    public function getEmail(): string
    {
        return $this->input('email');
    }

    public function getRoleId(): string
    {
        return $this->input('role_id');
    }
}
