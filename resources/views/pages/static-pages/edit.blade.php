<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.edit_page')">

    <div class="page-create">
        <div class="grid-2-items">
            <div class="card">
                <p class="title">@lang('pjstarter::pages.static_pages.edit_page') - {{ $staticPage->name }}</p>
                <x-pjutils::form
                    :action="route('api.static-pages.update', ['page' => $staticPage->id])"
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
                <p class="title">@lang('pjstarter::pages.static_pages.slug')</p>
            </div>
        </div>
    </div>

</x-pjstarter::layout.app>