<x-pjstarter::layout.app :title="__('pjstarter::general.dashboard')">

    <div class="dashboard">
        <div class="dashboard-welcome">
            <h2>@lang('pjstarter::pages.dashboard.welcome')</h2>
            <p>@lang($stats->hasStats ? 'pjstarter::pages.dashboard.welcome_subtitle' : 'pjstarter::pages.dashboard.welcome_subtitle_empty')</p>
        </div>

        @if($stats->hasStats)
            <x-pjutils::widget-grid :cols="4">
                @if($stats->articleCount !== null)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.articles')"
                        :value="$stats->articleCount"
                    />
                @endif

                @if($stats->categoryCount !== null)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.categories')"
                        :value="$stats->categoryCount"
                    />
                @endif

                @if($stats->authorCount !== null)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.authors')"
                        :value="$stats->authorCount"
                    />
                @endif

                @if($stats->staticPageCount !== null)
                    <x-pjutils::stat-card
                        :label="__('pjstarter::pages.dashboard.static_pages')"
                        :value="$stats->staticPageCount"
                    />
                @endif
            </x-pjutils::widget-grid>
        @endif
    </div>

</x-pjstarter::layout.app>
