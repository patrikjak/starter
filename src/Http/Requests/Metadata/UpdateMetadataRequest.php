<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Metadata;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Starter\Dto\Metadata\UpdateMetadata;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class UpdateMetadataRequest extends FormRequest
{
    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:191'],
            'description' => ['nullable', 'max:191'],
            'keywords' => ['nullable', 'max:191'],
            'canonical_url' => ['nullable', 'url', 'max:191'],
            'structured_data' => ['nullable', 'json'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'title.max' => trans_choice('pjutils::validation.max.string', 191),
            'description.max' => trans_choice('pjutils::validation.max.string', 191),
            'keywords.max' => trans_choice('pjutils::validation.max.string', 191),
            'canonical_url.url' => __('pjstarter::validation.url'),
            'canonical_url.max' => trans_choice('pjutils::validation.max.string', 191),
            'structured_data.json' => __('pjstarter::validation.json'),
        ];
    }

    /**
     * @return array<string>
     */
    public function attributes(): array
    {
        return [
            'title' => __('pjstarter::pages.metadata.meta_title'),
            'description' => __('pjstarter::pages.metadata.meta_description'),
            'keywords' => __('pjstarter::pages.metadata.meta_keywords'),
            'canonical_url' => __('pjstarter::pages.metadata.canonical_url'),
            'structured_data' => __('pjstarter::pages.metadata.structured_data'),
        ];
    }

    public function getUpdateMetadata(): UpdateMetadata
    {
        return new UpdateMetadata(
            $this->input('title'),
            $this->input('description'),
            $this->input('keywords'),
            $this->input('canonical_url'),
            $this->input('structured_data'),
        );
    }
}
