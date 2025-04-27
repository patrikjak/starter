@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.title')">

    @can(BasePolicy::VIEW_ANY, ArticleCategory::class)
        <x-slot:actions>
            <x-pjstarter::layout.action>
                <a href="{{ route('articles.categories.index') }}">
                    @lang('pjstarter::pages.articles.categories.title')
                </a>
            </x-pjstarter::layout.action>

            <x-pjstarter::layout.action>
                <a href="{{ route('articles.create') }}">
                    @lang('pjstarter::pages.articles.create')
                </a>
            </x-pjstarter::layout.action>
        </x-slot:actions>
    @endcan

    <div class="articles">

    </div>

</x-pjstarter::layout.app>