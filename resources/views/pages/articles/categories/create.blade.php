<x-pjstarter::layout.app :title="__('pjstarter::pages.articles.categories.create_new_category')">

    <div class="card w-3/5 mx-auto">
        <p class="title">@lang('pjstarter::pages.articles.categories.category_details')</p>

        <x-pjutils::form
            method="POST"
            :action="route('api.articles.categories.store')"
            :redirect="route('articles.categories.index')"
            :action-label="__('pjstarter::general.save')"
        >
            <x-pjutils::form.input name="name" :label="__('pjstarter::pages.articles.categories.name')" autofocus />

            <x-pjutils::form.textarea name="description" :label="__('pjstarter::pages.articles.categories.description')" />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>