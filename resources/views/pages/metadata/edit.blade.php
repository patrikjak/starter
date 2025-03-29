<x-pjstarter::layout.app
    :title="sprintf(
        '%s - %s (%s)',
        __('pjstarter::pages.metadata.title'),
        $metadata->metadatable->name,
        $metadata->metadatable->getMetadatableTypeLabel(),
    )"
>

    <div class="card mx-auto w-4/5">
        <p class="title">@lang('pjstarter::pages.metadata.edit')</p>

        <x-pjutils::form
            method="PUT"
            :action="route('api.metadata.update', ['metadata' => $metadata->id])"
            :action-label="__('pjstarter::general.edit')"
            :redirect="route('metadata.index')"
        >
            <x-pjutils::form.input
                name="title"
                :label="__('pjstarter::pages.metadata.meta_title')"
                :value="$metadata->title"
            />
            <x-pjutils::form.input name="description" :label="__('pjstarter::pages.metadata.meta_description')" :value="$metadata->description" />
            <x-pjutils::form.input name="keywords" :label="__('pjstarter::pages.metadata.meta_keywords')" :value="$metadata->keywords" />
            <x-pjutils::form.input name="canonical_url" :label="__('pjstarter::pages.metadata.canonical_url')" :value="$metadata->canonical_url" />
            <x-pjutils::form.textarea name="structured_data" :label="__('pjstarter::pages.metadata.structured_data')" :value="$metadata->structured_data" />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>