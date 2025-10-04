@php
    /**
     * @var array<\Patrikjak\Starter\Dto\Common\NavigationItem> $items
     * @var array<\Patrikjak\Starter\Dto\Common\NavigationItem> $userItems
     */
@endphp

<div class="navigation">
    <div class="app-name">
        <div class="initials">
            {{ $appNameInitials }}
        </div>

        <a href="{{ $homeUrl }}">{{ $appName }}</a>
    </div>

    <div class="items">
        @foreach($items as $item)
            <x-pjstarter::navigation-item :$item />
        @endforeach
    </div>

    @if($authFeaturesEnabled)
        <div class="user">
            <div class="initials">
                {{ $userNameInitials }}
            </div>

            <div class="info">
                <div class="name">
                    {{ $userName }}
                </div>
                <div class="email">
                    {{ $userEmail }}
                </div>
            </div>

            <div class="button">
                <x-pjutils::arrow direction="right"/>
            </div>
        </div>
    @endif
</div>

@if($authFeaturesEnabled)
    <div class="user-items">
        @if($userItems !== [])
            <div class="group">
                @foreach($userItems as $item)
                    <a
                            href="{{ $item->getUrl() }}"
                            class="{{ $item->classes }}"
                    >
                        <span class="text">{{ $item->label }}</span>
                    </a>
                @endforeach
            </div>
        @endif
        <div class="group">
            <form method="POST" action="{{ route('api.logout') }}" class="item logout-form">
                @csrf
                <a href="{{ route('api.logout') }}" class="text">@lang('pjstarter::general.logout')</a>
            </form>
        </div>
    </div>
@endif