@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')
@use('Patrikjak\Starter\Models\Articles\Article')

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.title')">

        <x-slot:actions>
            @can(BasePolicy::VIEW_ANY, ArticleCategory::class)
                <x-pjutils::button href="{{ route('admin.articles.categories.index') }}" :bordered="true">
                    @lang('pjstarter::pages.articles.categories.title')
                </x-pjutils::button>
            @endcan

            @can(BasePolicy::CREATE, Article::class)
                <x-pjutils::button href="{{ route('admin.articles.create') }}">
                    @lang('pjstarter::pages.articles.create')
                </x-pjutils::button>
            @endcan
        </x-slot:actions>

    <div class="articles">
        <x-pjutils.table::table :$table />
    </div>

</x-pjstarter::layout.app>
