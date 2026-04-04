@use('Patrikjak\Starter\Dto\Common\NavigationGroup')
@use('Patrikjak\Starter\Dto\Common\NavigationItem')

@php
    /**
     * @var array<NavigationGroup> $groups
     * @var array<NavigationItem> $userItems
     */
@endphp

<div class="navigation">
    <button id="nav-collapse-btn" aria-label="Toggle navigation">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>

    <div class="app-name">
        <div class="initials">
            {{ $appNameInitials }}
        </div>

        <a href="{{ $homeUrl }}">{{ $appName }}</a>
    </div>


    <div class="items">
        @foreach($groups as $group)
            <div class="nav-group">
                <p class="nav-group-label">{{ $group->label }}</p>

                @foreach($group->items as $item)
                    <x-pjstarter::navigation-item :$item />
                @endforeach
            </div>
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

        <div class="user-items">
            @if($userItems !== [])
                <div class="group">
                    @foreach($userItems as $item)
                        <a href="{{ $item->getUrl() }}" class="{{ $item->classes }}">
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
</div>
