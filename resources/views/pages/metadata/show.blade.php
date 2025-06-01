@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Metadata\Metadata')

<x-pjstarter::layout.app
    :title="sprintf(
        '%s - %s (%s)',
        __('pjstarter::pages.metadata.title'),
        $metadata->metadatable->name,
        $metadata->metadatable->getMetadatableTypeLabel(),
    )"
>

    @can(BasePolicy::EDIT, Metadata::class)
        <x-slot:actions>
            <x-pjstarter::layout.action>
                <a href="{{ route('admin.metadata.edit', ['metadata' => $metadata->id]) }}">@lang('pjstarter::general.edit')</a>
            </x-pjstarter::layout.action>
        </x-slot:actions>
    @endcan

    <div class="card w-4/5 mx-auto">
        <div class="data">
            <p class="font-primary font-bold">@lang('pjstarter::pages.metadata.meta_title')</p>
            <p>{{ $metadata->title }}</p>
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::pages.metadata.meta_description')</p>
            @isset($metadata->description)
                <p>{{ $metadata->description }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::pages.metadata.meta_keywords')</p>
            @isset($metadata->keywords)
                <p>{{ $metadata->keywords }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::pages.metadata.canonical_url')</p>
            @isset($metadata->canonical_url)
                <p>{{ $metadata->canonical_url }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::pages.metadata.structured_data')</p>
            @isset($metadata->structured_data)
                <p>{{ $metadata->structured_data }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::general.created_at')</p>
            @isset($metadata->created_at)
                <p>{{ $metadata->created_at->format('d.m.Y H:i') }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

        <div class="data mt-[3%]">
            <p class="font-primary font-bold">@lang('pjstarter::general.updated_at')</p>
            @isset($metadata->updated_at)
                <p>{{ $metadata->updated_at->format('d.m.Y H:i') }}</p>
            @else
                <p class="italic font-sm">@lang('pjstarter::pages.metadata.empty')</p>
            @endif
        </div>

    </div>

</x-pjstarter::layout.app>