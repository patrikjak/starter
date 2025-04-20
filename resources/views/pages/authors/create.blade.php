<x-pjstarter::layout.app :title="__('pjstarter::pages.authors.create_new_author')">

    <div class="card w-3/5 mx-auto">
        <p class="title">@lang('pjstarter::pages.authors.author_details')</p>

        <x-pjutils::form
            method="POST"
            :action="route('api.authors.store')"
            :redirect="route('authors.index')"
        >
            <x-pjutils::form.input name="name" :label="__('pjstarter::pages.authors.name')" autofocus />

            <x-pjutils::photo-uploader
                name="profile_picture"
                :label="__('pjstarter::pages.authors.profile_picture')"
            />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>