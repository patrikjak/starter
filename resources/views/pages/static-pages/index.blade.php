@use('Illuminate\Support\Facades\Vite')
@use('Patrikjak\Starter\Models\StaticPages\StaticPage')
@use('Patrikjak\Starter\Policies\BasePolicy')

@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/static-pages/static-pages-index.ts') }}" defer type="module"></script>
    @else
        <script src="{{ asset('vendor/pjstarter/assets/static-pages-index.js') }}" defer type="module"></script>
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.title')">

    @can(BasePolicy::CREATE, StaticPage::class)
        <x-slot:actions>
            <x-pjstarter::layout.action>
                <a href="{{ route('static-pages.create') }}">@lang('pjstarter::general.create')</a>
            </x-pjstarter::layout.action>
        </x-slot:actions>
    @endcan

    <x-pjutils.table::table :$table />

</x-pjstarter::layout.app>