@use('Illuminate\Support\Facades\Vite')

@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/static-pages/index.ts') }}" defer type="module"></script>
    @else
        {{-- TODO: pages script --}}
        <link rel="stylesheet" href="{{ asset('vendor/pjstarter/assets/page-slugs.css') }}">
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.title')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('static-pages.create') }}">@lang('pjstarter::general.create')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

    <x-pjutils.table::table :$table />

</x-pjstarter::layout.app>