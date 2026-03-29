<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.details')">

    <x-pjutils::details class="w-4/5 mx-auto">
        @if($canSeeId)
            <x-pjutils::details.row :label="__('pjstarter::general.id')">
                {{ $role->id }}
            </x-pjutils::details.row>
        @endif

        <x-pjutils::details.row :label="__('pjstarter::pages.users.roles.name')">
            {{ $role->name }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.users.roles.permissions')">
            {{ $permissions }}
        </x-pjutils::details.row>
    </x-pjutils::details>

</x-pjstarter::layout.app>
