@push('scripts')
    @if(file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <script src="{{ Vite::asset('resources/js/users/roles-index.ts') }}" defer type="module"></script>
    @else
        <script src="{{ asset('vendor/pjstarter/assets/roles-index.js') }}" type="module"></script>
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.title')">

    <div class="users-role">
        <x-pjutils.table::table :table="$rolesTable" />
    </div>

</x-pjstarter::layout.app>