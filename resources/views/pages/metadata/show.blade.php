@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Metadata\Metadata')

<x-pjstarter::layout.app
    :title="sprintf(
        '%s: %s (%s)',
        __('pjstarter::pages.metadata.title'),
        $metadata->metadatable->getPageName(),
        $metadata->metadatable->getMetadatableTypeLabel(),
    )"
>

    @can(BasePolicy::EDIT, Metadata::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.metadata.edit', ['metadata' => $metadata->id]) }}">
                @lang('pjstarter::general.edit')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <x-pjutils::details class="w-4/5 mx-auto">
        <x-pjutils::details.row :label="__('pjstarter::pages.metadata.meta_title')">
            {{ $metadata->title }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.metadata.meta_description')">
            {{ $metadata->description ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.metadata.meta_keywords')">
            {{ $metadata->keywords ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.metadata.canonical_url')">
            {{ $metadata->canonical_url ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.metadata.structured_data')">
            {{ $metadata->structured_data ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::general.created_at')">
            {{ $metadata->created_at?->format('d.m.Y H:i') ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::general.updated_at')">
            {{ $metadata->updated_at?->format('d.m.Y H:i') ?? __('pjstarter::pages.metadata.empty') }}
        </x-pjutils::details.row>
    </x-pjutils::details>

</x-pjstarter::layout.app>
