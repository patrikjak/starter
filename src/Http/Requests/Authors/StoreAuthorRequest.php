<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Authors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;
use Patrikjak\Utils\Common\Http\Requests\Traits\FileUpload;

class StoreAuthorRequest extends FormRequest
{
    use FileUpload;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:191'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::NEUTER),
            'name.max' => trans_choice('pjutils::validation.max.string', 191),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.authors.name'),
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getProfilePicture(): ?UploadedFile
    {
        return $this->file('profile_picture');
    }
}
