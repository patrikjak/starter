<x-pjstarter::layout.app :title="__('pjstarter::pages.profile.title')">

    <x-slot:actions>
        <x-pjutils::button href="{{ route('admin.change-password') }}">
            @lang('pjstarter::pages.profile.change_password')
        </x-pjutils::button>
    </x-slot:actions>

    <div class="profile">
        <x-pjutils::details class="w-1/2 mx-auto">
            <x-pjutils::details.row :label="__('pjstarter::pages.profile.name')">
                {{ $user->name }}
            </x-pjutils::details.row>

            <x-pjutils::details.row :label="__('pjstarter::pages.profile.email')">
                {{ $user->email }}
            </x-pjutils::details.row>

            <x-pjutils::details.row :label="__('pjstarter::pages.profile.member_since')">
                {{ $user->created_at->format('d.m.Y') }}
            </x-pjutils::details.row>
        </x-pjutils::details>
    </div>

</x-pjstarter::layout.app>
