<x-pjstarter::layout.app :title="sprintf(
    '%s - %s',
    __('pjstarter::pages.articles.categories.edit'),
    $articleCategory->name,
)">

    <div class="page-create">
        <div class="grid-2-items">
            <div class="card">
                <p class="title">@lang('pjstarter::pages.articles.categories.category_details')</p>
                <x-pjutils::form
                    :action="route('api.articles.categories.update', ['articleCategory' => $articleCategory->id])"
                    method="PUT"
                    :action-label="__('pjstarter::general.edit')"
                    :redirect="route('articles.categories.index')"
                >
                    <x-pjutils::form.input
                        name="name"
                        :label="__('pjstarter::pages.articles.categories.name')"
                        :value="$articleCategory->name"
                    />

                    <x-pjutils::form.textarea
                        name="description"
                        :label="__('pjstarter::pages.articles.categories.description')"
                        :value="$articleCategory->description"
                    />
                </x-pjutils::form>
            </div>

            <div class="card">
                <p class="title">@lang('pjstarter::pages.slug.url')</p>

                <x-pjutils::form
                    :action="route('api.slugs.update', ['slug' => $articleCategory->slug->id])"
                    method="PUT"
                    :action-label="__('pjstarter::general.edit')"
                    :redirect="route('articles.categories.index')"
                >
                    <x-pjutils::form.input
                        name="prefix"
                        :label="__('pjstarter::pages.slug.prefix')"
                        :value="$articleCategory->slug->prefix"
                    />

                    <x-pjutils::form.input
                        name="slug"
                        :label="__('pjstarter::pages.slug.slug')"
                        :value="$articleCategory->slug->slug"
                    />
                </x-pjutils::form>
            </div>
        </div>
    </div>

</x-pjstarter::layout.app>