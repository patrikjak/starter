<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.edit', ['name' => $role->name])">

    <div class="page-create">
        <div class="card">
            <x-pjutils::form
                method="PUT"
                :action="route('admin.api.users.roles.update', ['role' => $role->id])"
                :redirect="route('admin.users.roles.index')"
                :action-label="__('pjstarter::general.save')"
            >
                <x-pjutils::form.input name="name" :label="__('pjstarter::pages.users.roles.name')" :value="$role->name" autofocus />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>
