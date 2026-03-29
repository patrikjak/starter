@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.articles.categories.category_details'), $articleCategory->name)">

    @can(BasePolicy::EDIT, ArticleCategory::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.articles.categories.edit', ['articleCategory' => $articleCategory->id]) }}">
                @lang('pjstarter::general.edit')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <x-pjutils::details class="w-3/5 mx-auto">
        <x-pjutils::details.row :label="__('pjstarter::pages.articles.categories.name')">
            {{ $articleCategory->name }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.articles.categories.description')">
            {{ $articleCategory->description ?? __('pjstarter::general.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.slug.url')">
            <a href="{{ $articleCategory->getUrl() }}" class="font-primary">{{ $articleCategory->getUrl() }}</a>
        </x-pjutils::details.row>
    </x-pjutils::details>

</x-pjstarter::layout.app>
