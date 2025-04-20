@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Users\Role')
@use('Patrikjak\Starter\Models\Users\Permission')

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.title')">

    <x-slot:actions>
        @can(BasePolicy::VIEW_ANY, Role::class)
            <x-pjstarter::layout.action>
                <a href="{{ route('users.roles.index') }}">@lang('pjstarter::pages.users.roles.title')</a>
            </x-pjstarter::layout.action>
        @endcan

        @can(BasePolicy::VIEW_ANY, Permission::class)
            <x-pjstarter::layout.action>
                <a href="{{ route('users.permissions.index') }}">@lang('pjstarter::pages.users.permissions.title')</a>
            </x-pjstarter::layout.action>
        @endcan
    </x-slot:actions>

    <div class="users">
        <x-pjutils.table::table :table="$usersTable" />
    </div>

</x-pjstarter::layout.app>