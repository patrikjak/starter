<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.new_page')">

    <div class="page-create">
        <div class="card w-3/5 mx-auto">
            <p class="title">@lang('pjstarter::pages.static_pages.create_new_page')</p>
            <x-pjutils::form
                :action="route('api.static-pages.store')"
                method="POST"
                :action-label="__('pjstarter::general.create')"
                :redirect="route('static-pages.index')"
            >
                <x-pjutils::form.input name="name" :label="__('pjstarter::pages.static_pages.name')" />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>