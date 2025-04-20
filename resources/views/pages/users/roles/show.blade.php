<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.details')">

    <div class="card w-4/5 mx-auto">
        @if($canSeeId)
            <p class="font-primary font-bold">@lang('pjstarter::general.id')</p>
            <p>{{ $role->id }}</p>
        @endif

        <p @class(['font-primary', 'font-bold', 'mt-[3%]' => $canSeeId])>@lang('pjstarter::pages.users.roles.name')</p>
        <p>{{ $role->name }}</p>

        <p class="font-primary font-bold mt-[3%]">@lang('pjstarter::pages.users.roles.permissions')</p>
        <p>{{ $permissions }}</p>
    </div>

</x-pjstarter::layout.app>