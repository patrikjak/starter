<x-pjstarter::layout.app :title="__('pjstarter::pages.users.title')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('users.roles.index') }}">@lang('pjstarter::pages.users.roles.title')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

    <div class="users">
        <x-pjutils.table::table :table="$usersTable" />
    </div>

</x-pjstarter::layout.app>