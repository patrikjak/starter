<x-pjstarter::layout.app :title="__('pjstarter::pages.users.permissions.title')">

    <div class="users-permissions">
        <x-pjutils.table::table :table="$permissionsTable" />
    </div>

</x-pjstarter::layout.app>