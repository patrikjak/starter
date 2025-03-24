@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/metadata/metadata-index.ts') }}" defer type="module"></script>
    @else
        <script src="{{ asset('vendor/pjstarter/assets/metadata-index.js') }}" type="module"></script>
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::pages.metadata.title')">

    <x-pjutils.table::table :$table />

</x-pjstarter::layout.app>