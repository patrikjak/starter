@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Users\Role')
@use('Patrikjak\Starter\Models\Users\Permission')

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.title')">

    <x-slot:actions>
        @can(BasePolicy::VIEW_ANY, Role::class)
            <x-pjutils::button href="{{ route('admin.users.roles.index') }}" :bordered="true">
                @lang('pjstarter::pages.users.roles.title')
            </x-pjutils::button>
        @endcan

        @can(BasePolicy::VIEW_ANY, Permission::class)
            <x-pjutils::button href="{{ route('admin.users.permissions.index') }}" :bordered="true">
                @lang('pjstarter::pages.users.permissions.title')
            </x-pjutils::button>
        @endcan
    </x-slot:actions>

    <div class="users">
        <x-pjutils.table::table :table="$usersTable" />
    </div>

</x-pjstarter::layout.app>
