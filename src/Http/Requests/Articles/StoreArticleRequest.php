<?php

namespace Patrikjak\Starter\Http\Requests\Articles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;
use Patrikjak\Starter\Dto\Articles\ArticleInputData;
use Patrikjak\Starter\Enums\Articles\ArticleStatus;
use Patrikjak\Starter\Enums\Articles\Visibility;
use Patrikjak\Starter\Factories\Editorjs\EditorDataFactory;
use Patrikjak\Starter\Repositories\Contracts\Articles\ArticleCategoryRepository;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class StoreArticleRequest extends FormRequest
{
    /**
     * @return array<string, array<string|Enum|In>>
     */
    public function rules(): array
    {
        $categories = app(ArticleCategoryRepository::class)->getAll()->pluck('id')->toArray();
        $authors = app(AuthorRepository::class)->getAll()->pluck('id')->toArray();

        return [
            'title' => ['required', 'max:191'],
            'category' => ['required', Rule::in($categories)],
            'author' => ['required', Rule::in($authors)],
            'excerpt' => ['nullable'],
            'content' => ['required', 'array'],
            'content.blocks' => ['required', 'array', 'min:1'],
            'feature_image' => ['nullable', 'image'],
            'status' => ['required', Rule::enum(ArticleStatus::class)],
            'read_time' => ['nullable', 'integer'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'category.required' => trans_choice('pjutils::validation.required', GrammaticalGender::FEMININE),
            'category.in' => __('pjstarter::validation.in'),
            'author.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'author.in' => __('pjstarter::validation.in'),
            'name.max' => trans_choice('pjutils::validation.max.string', 191),
            'content.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'content.blocks.required' => trans_choice('pjutils::validation.required', GrammaticalGender::MASCULINE),
            'read_time.integer' => __('pjstarter::validation.integer'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => __('pjstarter::pages.articles.article_title'),
            'category' => __('pjstarter::pages.articles.category'),
            'author' => __('pjstarter::pages.articles.author'),
            'excerpt' => __('pjstarter::pages.articles.excerpt'),
            'content' => __('pjstarter::pages.articles.content'),
            'content.blocks' => __('pjstarter::pages.articles.content'),
            'feature_image' => __('pjstarter::pages.articles.featured_image'),
            'status' => __('pjstarter::pages.articles.status'),
            'read_time' => __('pjstarter::pages.articles.read_time'),
        ];
    }

    public function getArticleInputData(): ArticleInputData
    {
        $content = EditorDataFactory::createFromOutputData($this->input('content'));

        return new ArticleInputData(
            $this->input('author'),
            $this->input('category'),
            $this->input('title'),
            $this->input('excerpt'),
            $content,
            $this->file('featured_image'),
            ArticleStatus::from($this->input('status')),
            Visibility::PUBLIC,
            $this->integer('read_time', null),
        );
    }

    protected function prepareForValidation(): void
    {
        $content = json_decode($this->input('content'), true);

        $this->merge(['content' => $content]);
    }
}
