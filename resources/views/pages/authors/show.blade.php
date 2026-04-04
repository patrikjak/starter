@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Authors\Author')

<x-pjstarter::layout.app :title="sprintf('%s: %s', __('pjstarter::pages.authors.author_details'), $author->name)">

    @can(BasePolicy::EDIT, Author::class)
        <x-slot:actions>
            <x-pjutils::button href="{{ route('admin.authors.edit', ['author' => $author->id]) }}">
                @lang('pjstarter::pages.authors.edit_author')
            </x-pjutils::button>
        </x-slot:actions>
    @endcan

    <x-pjutils::details class="w-3/5 mx-auto">
        <x-pjutils::details.row :label="__('pjstarter::pages.authors.name')">
            {{ $author->name }}
        </x-pjutils::details.row>

        <x-pjutils::details.row :label="__('pjstarter::pages.authors.profile_picture')">
            @isset($author->profile_picture)
                <img src="{{ $author->getProfilePicturePath() }}" alt="profile picture" class="max-w-52 max-h-52 object-contain">
            @else
                -
            @endisset
        </x-pjutils::details.row>
    </x-pjutils::details>

</x-pjstarter::layout.app>
