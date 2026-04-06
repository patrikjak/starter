<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.create')">

    <div class="card w-3/5 mx-auto">
        <x-pjutils::form
            method="POST"
            :action="route('admin.api.users.roles.store')"
            :redirect="route('admin.users.roles.index')"
            :action-label="__('pjstarter::general.save')"
        >
            <x-pjutils::form.input name="name" :label="__('pjstarter::pages.users.roles.name')" autofocus />
            <x-pjutils::form.input name="slug" :label="__('pjstarter::pages.users.roles.slug')" />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>
