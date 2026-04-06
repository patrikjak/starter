@use('Patrikjak\Starter\Models\Users\Role')
@use('Patrikjak\Starter\Policies\BasePolicy')

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.title')">

    @can(BasePolicy::CREATE, Role::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.users.roles.create') }}">
                @lang('pjstarter::pages.users.roles.create')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <div class="users-role">
        <x-pjutils.table::table :table="$rolesTable" />
    </div>

</x-pjstarter::layout.app>