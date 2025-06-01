<div @class(['item-wrapper', 'has-sub-items' => $item->subItems !== [], 'active' => $hasActiveSubItem()])>
    <a
        href="{{ $item->getUrl() }}"
        class="{{ $item->classes }}"
    >
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