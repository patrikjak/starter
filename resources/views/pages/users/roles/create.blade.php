<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.create')">

    <div class="page-create">
        <div class="card">
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
    </div>

</x-pjstarter::layout.app>
