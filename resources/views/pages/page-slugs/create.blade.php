<x-pjstarter::layout.app :title="__('pjstarter::pages.metadata.pages.page_creation')">

    <div class="page-create">
        <div class="card w-3/5 mx-auto">
            <p class="title">@lang('pjstarter::pages.metadata.pages.create')</p>
            <x-pjutils::form
                :action="route('api.page-slugs.store')"
                method="POST"
                :action-label="__('pjstarter::pages.metadata.pages.create')"
                :redirect="route('page-slugs.index')"
            >
                <x-pjutils::form.input name="name" :label="__('pjstarter::pages.metadata.pages.name')" />
                <x-pjutils::form.input name="slug" :label="__('pjstarter::pages.metadata.pages.slug')" />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>