@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\Article')
@use('Patrikjak\Starter\Models\Authors\Author')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.articles.detail'), $article->title)">

    @can(BasePolicy::EDIT, Article::class)
        <x-slot:actions>
            <x-pjstarter::action>
                <a href="{{ route('articles.edit', ['article' => $article->id]) }}">
                    @lang('pjstarter::general.edit')
                </a>
            </x-pjstarter::action>
        </x-slot:actions>
    @endcan

    <div class="card w-3/5 mx-auto">
        <p class="font-primary font-bold">@lang('pjstarter::pages.articles.article_title')</p>
        <p>{{ $article->title }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.category')</p>
        @can(BasePolicy::VIEW, ArticleCategory::class)
            <a href="{{ route('articles.categories.show', ['articleCategory' => $article->articleCategory->id]) }}" class="font-primary">
                {{ $article->articleCategory->name }}
            </a>
        @else
            <p>{{ $article->articleCategory->name }}</p>
        @endcan

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.author')</p>
        @can(BasePolicy::VIEW, Author::class)
            <a href="{{ route('authors.show', ['author' => $article->author->id]) }}" class="font-primary">
                {{ $article->author->name }}
            </a>
        @else
            <p>{{ $article->author->name }}</p>
        @endcan

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.excerpt')</p>
        @isset($article->excerpt)
            <p>{{ $article->excerpt }}</p>
        @else
            <p>-</p>
        @endisset

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.featured_image')</p>
        @isset($article->featured_image)
            <img src="{{ $article->getFeaturedImagePath() }}" alt="featured image" class="max-w-52 max-h-52 object-contain mt-4 block">
        @else
            <p>-</p>
        @endif

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.status')</p>
        <p>{{ $article->status->toLabel() }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.read_time')</p>
        @isset($article->read_time)
            <p>{{ $article->read_time }} @choice('pjstarter::general.minute', $article->read_time)</p>
        @else
            <p>-</p>
        @endisset

        <p class="font-primary font-bold mt-2">@lang('pjstarter::general.created_at')</p>
        <p>{{ $article->created_at->format('d.m.Y H:i') }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::general.updated_at')</p>
        <p>{{ $article->updated_at->format('d.m.Y H:i') }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.published_at')</p>
        <p>{{ $article->published_at !== null ? $article->published_at->format('d.m.Y H:i') : '-' }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.slug.url')</p>
        <a href="{{ $article->getUrl() }}" class="font-primary">{{ $article->getUrl() }}</a>
    </div>

</x-pjstarter::layout.app>