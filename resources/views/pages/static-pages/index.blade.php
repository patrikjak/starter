@use('Patrikjak\Starter\Models\StaticPages\StaticPage')
@use('Patrikjak\Starter\Policies\BasePolicy')

<x-pjstarter::layout.app :title="__('pjstarter::pages.static_pages.title')">

    @can(BasePolicy::CREATE, StaticPage::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.static-pages.create') }}">
                @lang('pjstarter::general.create')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <x-pjutils.table::table :$table />

</x-pjstarter::layout.app>
