@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\Article')
@use('Patrikjak\Starter\Models\Authors\Author')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')
@use('Patrikjak\Starter\Enums\Articles\ArticleStatus')
@use('Patrikjak\Utils\Common\Enums\BadgeType')

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.articles.detail'), $article->title)">

    @can(BasePolicy::EDIT, Article::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.articles.edit', ['article' => $article->id]) }}">
                @lang('pjstarter::general.edit')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <x-pjutils::details class="w-3/5 mx-auto">
        <x-pjutils::details.row :label="__('pjstarter::pages.articles.article_title')">
            {{ $article->title }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.category')">
            @can(BasePolicy::VIEW, ArticleCategory::class)
                <a href="{{ route('admin.articles.categories.show', ['articleCategory' => $article->articleCategory->id]) }}" class="font-primary">
                    {{ $article->articleCategory->name }}
                </a>
            @else
                {{ $article->articleCategory->name }}
            @endcan
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.author')">
            @can(BasePolicy::VIEW, Author::class)
                <a href="{{ route('admin.authors.show', ['author' => $article->author->id]) }}" class="font-primary">
                    {{ $article->author->name }}
                </a>
            @else
                {{ $article->author->name }}
            @endcan
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.excerpt')">
            {{ $article->excerpt ?? '-' }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.featured_image')">
            @isset($article->featured_image)
                <img src="{{ $article->getFeaturedImagePath() }}" alt="featured image" class="max-w-52 max-h-52 object-contain">
            @else
                -
            @endisset
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.status')">
            <x-pjutils::badge :type="match($article->status) {
                ArticleStatus::PUBLISHED => BadgeType::SUCCESS,
                ArticleStatus::DRAFT => BadgeType::WARNING,
                ArticleStatus::ARCHIVED => BadgeType::DEFAULT,
            }">
                {{ $article->status->toLabel() }}
            </x-pjutils::badge>
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.read_time')">
            @isset($article->read_time)
                {{ $article->read_time }} @choice('pjstarter::general.minute', $article->read_time)
            @else
                -
            @endisset
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::general.created_at')">
            {{ $article->created_at->format('d.m.Y H:i') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::general.updated_at')">
            {{ $article->updated_at->format('d.m.Y H:i') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.published_at')">
            {{ $article->published_at !== null ? $article->published_at->format('d.m.Y H:i') : '-' }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.slug.url')">
            <a href="{{ $article->getUrl() }}" class="font-primary">{{ $article->getUrl() }}</a>
        </x-pjutils::details.row>
    </x-pjutils::details>

</x-pjstarter::layout.app>
