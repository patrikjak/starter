<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.details')">

    <div class="card w-4/5 mx-auto">
        @if($canSeeId)
            <p class="font-primary strong">@lang('pjstarter::general.id')</p>
            <p>{{ $role->id }}</p>
        @endif

        <p @class(['font-primary', 'strong', 'mt-1%' => $canSeeId])>@lang('pjstarter::pages.users.roles.name')</p>
        <p>{{ $role->name }}</p>

        <p class="font-primary strong mt-1%">@lang('pjstarter::pages.users.roles.permissions')</p>
        <p>{{ $permissions }}</p>
    </div>

</x-pjstarter::layout.app>