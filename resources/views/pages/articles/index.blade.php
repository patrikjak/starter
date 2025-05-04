@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')
@use('Patrikjak\Starter\Models\Articles\Article')

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.title')">

        <x-slot:actions>
            @can(BasePolicy::VIEW_ANY, ArticleCategory::class)
                <x-pjstarter::layout.action>
                    <a href="{{ route('articles.categories.index') }}">
                        @lang('pjstarter::pages.articles.categories.title')
                    </a>
                </x-pjstarter::layout.action>
            @endcan

            @can(BasePolicy::CREATE, Article::class)
                <x-pjstarter::layout.action>
                    <a href="{{ route('articles.create') }}">
                        @lang('pjstarter::pages.articles.create')
                    </a>
                </x-pjstarter::layout.action>
            @endcan
        </x-slot:actions>

    <div class="articles">
        <x-pjutils.table::table :$table />
    </div>

</x-pjstarter::layout.app>