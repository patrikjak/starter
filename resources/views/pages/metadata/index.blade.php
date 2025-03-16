<x-pjstarter::layout.app :title="__('pjstarter::pages.meta_data.title')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('metadata.pages.index') }}">@lang('pjstarter::pages.meta_data.pages.title')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

</x-pjstarter::layout.app>