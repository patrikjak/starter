@use('Patrikjak\Utils\Common\Enums\Icon')
@use('Illuminate\Support\Facades\Vite')

@push('styles')
    @if (file_exists(base_path('vendor/patrikjak/starter/public/hot')))
        {{ Vite::useHotFile('/var/www/vendor/patrikjak/starter/public/hot') }}
        <link rel="stylesheet" href="{{ Vite::asset('resources/css/profile.scss') }}">
    @else
        <link rel="stylesheet" href="{{ asset('vendor/pjstarter/assets/profile.css') }}">
    @endif
@endpush

<x-pjstarter::layout.app :title="__('pjstarter::general.profile')">

    <x-slot:actions>
        <x-pjstarter::layout.action>
            <a href="{{ route('change-password') }}">@lang('pjstarter::general.change_password')</a>
        </x-pjstarter::layout.action>
    </x-slot:actions>

    <div class="profile">
        <div class="card user-info w-2/5">
            <p class="title">{{ $user->name }}</p>
            <p>{!! Icon::getCustomAsHtml('email') !!} {{ $user->email }}</p>
            <p>{!! Icon::getCustomAsHtml('clock') !!} @lang('pjstarter::general.member_since') {{ $user->created_at->format('d.m.Y') }}</p>
        </div>
    </div>

</x-pjstarter::layout.app>