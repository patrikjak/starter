@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Articles\ArticleCategory')

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.authors.author_details'), $articleCategory->name)">

    @can(BasePolicy::EDIT, ArticleCategory::class)
        <x-slot:actions>
            <x-pjstarter::action>
                <a href="{{ route('articles.categories.edit', ['articleCategory' => $articleCategory->id]) }}">
                    @lang('pjstarter::general.edit')
                </a>
            </x-pjstarter::action>
        </x-slot:actions>
    @endcan

    <div class="card w-3/5 mx-auto">
        <p class="font-primary font-bold">@lang('pjstarter::pages.articles.categories.name')</p>
        <p>{{ $articleCategory->name }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.articles.categories.description')</p>
        <p @class(['italic' => $articleCategory->description === null])>
            {{ $articleCategory->description ?? __('pjstarter::general.empty') }}
        </p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.slug.url')</p>
        <p>{{ $articleCategory->getUrl() }}</p>
    </div>

</x-pjstarter::layout.app>