@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')

<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.categories.title')">

    @can(BasePolicy::VIEW_ANY, ArticleCategory::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.articles.categories.create') }}">
                @lang('pjstarter::pages.articles.categories.create_new_category')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <div class="article-categories">
        <x-pjutils.table::table :$table />
    </div>

</x-pjstarter::layout.app>
