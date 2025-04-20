<x-pjstarter::layout.app :title="__('pjstarter::pages.authors.author_edit')">

    <div class="card w-3/5 mx-auto">
        <p class="title">@lang('pjstarter::pages.authors.author_details') - {{ $author->name }}</p>

        <x-pjutils::form
            method="PUT"
            :action="route('api.authors.update', ['author' => $author->id])"
            :redirect="route('authors.index')"
        >
            <x-pjutils::form.input name="name" :label="__('pjstarter::pages.authors.name')" :value="$author->name" />

            <x-pjutils::photo-uploader
                name="profile_picture"
                :label="__('pjstarter::pages.authors.profile_picture')"
                :value="$profilePicture"
            />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>