<x-pjstarter::layout.app :title="sprintf(
    '%s - %s%s',
    __('pjstarter::pages.static_pages.edit_page'),
    $staticPage->slug->prefix === null ? '' : sprintf('%s/', $staticPage->slug->prefix),
    $staticPage->name,
)">

    <div class="page-create">
        <div class="grid-2-items">
            <div class="card">
                <p class="title">@lang('pjstarter::pages.static_pages.edit_page')</p>
                <x-pjutils::form
                    :action="route('api.static-pages.update', ['staticPage' => $staticPage->id])"
                    method="PUT"
                    :action-label="__('pjstarter::general.edit')"
                    :redirect="route('static-pages.index')"
                >
                    <x-pjutils::form.input
                        name="name"
                        :label="__('pjstarter::pages.static_pages.name')"
                        :value="$staticPage->name"
                    />
                </x-pjutils::form>
            </div>

            <div class="card">
                <p class="title">@lang('pjstarter::pages.static_pages.url')</p>

                <x-pjutils::form
                    :action="route('api.page-slugs.update', ['pageSlug' => $staticPage->slug->id])"
                    method="PUT"
                    :action-label="__('pjstarter::general.edit')"
                    :redirect="route('static-pages.index')"
                >
                    <x-pjutils::form.input
                        name="prefix"
                        :label="__('pjstarter::pages.static_pages.prefix')"
                        :value="$staticPage->slug->prefix"
                    />

                    <x-pjutils::form.input
                        name="slug"
                        :label="__('pjstarter::pages.static_pages.slug')"
                        :value="$staticPage->slug->slug"
                    />
                </x-pjutils::form>
            </div>
        </div>
    </div>

</x-pjstarter::layout.app>