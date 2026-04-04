@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Authors\Author')

<x-pjstarter::layout.app :title="__('pjstarter::pages.authors.title')">

    @can(BasePolicy::CREATE, Author::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.authors.create') }}">
                @lang('pjstarter::pages.authors.create_new_author')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <div class="authors">
        <x-pjutils.table::table :table="$authorsTable" />
    </div>

</x-pjstarter::layout.app>
