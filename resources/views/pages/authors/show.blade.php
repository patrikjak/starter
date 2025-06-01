@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Authors\Author')

<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.authors.author_details'), $author->name)">

    @can(BasePolicy::EDIT, Author::class)
        <x-slot:actions>
            <x-pjstarter::action>
                <a href="{{ route('admin.authors.edit', ['author' => $author->id]) }}">@lang('pjstarter::pages.authors.edit_author')</a>
            </x-pjstarter::action>
        </x-slot:actions>
    @endcan

    <div class="card w-3/5 mx-auto">
        <p class="font-primary font-bold">@lang('pjstarter::pages.authors.name')</p>
        <p>{{ $author->name }}</p>

        <p class="font-primary font-bold mt-2">@lang('pjstarter::pages.authors.profile_picture')</p>
        @isset($author->profile_picture)
            <img src="{{ $author->getProfilePicturePath() }}" alt="profile picture" class="max-w-52 max-h-52 object-contain mt-4 block">
        @else
            <p>-</p>
        @endisset
    </div>

</x-pjstarter::layout.app>