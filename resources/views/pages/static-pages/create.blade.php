<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.new_page')">

    <div class="page-create">
        <div class="card w-3/5 mx-auto">
            <h3 class="title">@lang('pjstarter::pages.static_pages.create_new_page')</h3>
            <x-pjutils::form
                :action="route('admin.api.static-pages.store')"
                method="POST"
                :action-label="__('pjstarter::general.create')"
                :redirect="route('admin.static-pages.index')"
            >
                <x-pjutils::form.input name="name" :label="__('pjstarter::pages.static_pages.name')" autofocus />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>