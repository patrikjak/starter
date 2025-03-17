@use('Patrikjak\Starter\Models\Metadata\Page')

@php
    /**
     * @var Page $page
     */
@endphp

<x-pjstarter::layout.app :title="__('pjstarter::pages.metadata.pages.page_editing')">

    <div class="page-create">
        <div class="card w-3/5 mx-auto">
            <p class="title">@lang('pjstarter::pages.metadata.pages.edit')</p>
            <x-pjutils::form
                :action="route('api.metadata.pages.update', ['page' => $page->id])"
                method="PUT"
                :action-label="__('pjstarter::pages.metadata.pages.edit')"
                :redirect="route('metadata.pages.index')"
            >
                <x-pjutils::form.input name="name" :label="__('pjstarter::pages.metadata.pages.name')" :value="$page->name" />
                <x-pjutils::form.input name="slug" :label="__('pjstarter::pages.metadata.pages.slug')" :value="$page->slug" />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>