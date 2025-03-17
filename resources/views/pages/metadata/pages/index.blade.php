@use('Illuminate\Support\Facades\Vite')

@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/metadata/pages.ts') }}" defer type="module"></script>
    @else
        {{-- TODO: pages script --}}
        <link rel="stylesheet" href="{{ asset('vendor/pjstarter/assets/profile.css') }}">
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::pages.metadata.pages.title')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('metadata.pages.create') }}">@lang('pjstarter::pages.metadata.pages.create')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

    <div class="pages-index">
        <x-pjutils.table::table :table="$pagesTable" />
    </div>

</x-pjstarter::layout.app>