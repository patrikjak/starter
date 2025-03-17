<x-pjstarter::layout.app :title="__('pjstarter::pages.metadata.title')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('metadata.pages.index') }}">@lang('pjstarter::pages.metadata.pages.title')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

</x-pjstarter::layout.app>