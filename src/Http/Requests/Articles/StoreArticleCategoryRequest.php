<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Http\Requests\Articles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Patrikjak\Starter\Dto\Articles\ArticleCategoryData;
use Patrikjak\Starter\Models\Articles\ArticleCategory;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class StoreArticleCategoryRequest extends FormRequest
{
    /**
     * @return array<string, array<string|Unique>>
     */
    public function rules(): array
    {
        $currentArticleCategory = $this->route('articleCategory');
        $uniqueRule = Rule::unique('article_categories', 'name');

        if ($currentArticleCategory instanceof ArticleCategory) {
            $uniqueRule->ignore($currentArticleCategory->id);
        }

        return [
            'name' => ['required', 'max:191', $uniqueRule],
            'description' => ['nullable'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'name.max' => trans_choice('pjutils::validation.max.string', 191),
            'name.unique' => __('pjutils::validation.unique'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('pjstarter::pages.articles.categories.name'),
            'description' => __('pjstarter::pages.articles.categories.description'),
        ];
    }

    public function getArticleCategoryData(): ArticleCategoryData
    {
        return new ArticleCategoryData($this->input('name'), $this->input('description'));
    }
}
