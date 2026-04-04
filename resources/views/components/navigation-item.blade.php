<div @class(['item-wrapper', 'has-sub-items' => $item->subItems !== [], 'active' => $hasActiveSubItem()])>
    <a
        href="{{ $item->getUrl() }}"
        class="{{ $item->classes }}"
        data-nav-tooltip="{{ $item->label }}"
    >
        <span class="nav-icon">
            @if($item->icon)
                {!! $item->icon !!}
            @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            @endif
        </span>
        <span class="text">{{ $item->label }}</span>

        @if($item->subItems !== [])
            <x-pjutils::arrow :direction="$hasActiveSubItem() ? 'up' : 'down'" class="arrow" />
        @endif
    </a>

    @empty(!$item->subItems)
        <div class="sub-items">
            @foreach($item->subItems as $subItem)
                <x-pjstarter::navigation-item :item="$subItem" />
            @endforeach
        </div>
    @endempty
</div>