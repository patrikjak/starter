<x-pjstarter::layout.app :title="__('pjstarter::pages.users.roles.edit', ['name' => $role->name])">

    <div class="card w-3/5 mx-auto">
        <x-pjutils::form
            method="PUT"
            :action="route('admin.api.users.roles.update', ['role' => $role->id])"
            :redirect="route('admin.users.roles.index')"
            :action-label="__('pjstarter::general.save')"
        >
            <x-pjutils::form.input name="name" :label="__('pjstarter::pages.users.roles.name')" :value="$role->name" autofocus />
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>
