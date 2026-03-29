<x-pjstarter::layout.app :title="__('pjstarter::general.dashboard')">

    <div class="dashboard">
        <div class="dashboard-welcome">
            <h2>@lang('pjstarter::pages.dashboard.welcome')</h2>
            <p>@lang($hasStats ? 'pjstarter::pages.dashboard.welcome_subtitle' : 'pjstarter::pages.dashboard.welcome_subtitle_empty')</p>
        </div>

        @if($articleCount !== null || $categoryCount !== null || $authorCount !== null || $staticPageCount !== null)
            <x-pjutils::widget-grid :cols="4">
                @isset($articleCount)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.articles')"
                        :value="$articleCount"
                    />
                @endisset

                @isset($categoryCount)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.categories')"
                        :value="$categoryCount"
                    />
                @endisset

                @isset($authorCount)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.authors')"
                        :value="$authorCount"
                    />
                @endisset

                @isset($staticPageCount)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.static_pages')"
                        :value="$staticPageCount"
                    />
                @endisset
            </x-pjutils::widget-grid>
        @endif
    </div>

</x-pjstarter::layout.app>
