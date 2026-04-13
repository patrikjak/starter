<x-pjstarter::layout.app :title="sprintf('%s: %s', __('pjstarter::pages.users.roles.manage_permissions'), $role->name)">

    <div class="manage-permissions">
        <x-pjutils::form
            method="PUT"
            :action="route('admin.api.users.roles.permissions', ['role' => $role->id])"
            :action-label="__('pjstarter::general.save')"
        >
            @foreach($availablePermissions as $feature => $permissions)
                <x-pjutils::widget :title="__(sprintf('pjstarter::permissions.features.%s', $feature))" class="mb-4">
                    <div class="permissions grid grid-cols-4 gap-4">
                        @foreach($permissions as $permission)
                            <x-pjutils::form.checkbox
                                :name="sprintf('permission_%s', $permission->name)"
                                :label="$permission->description"
                                :checked="in_array($permission->name, $assignedPermissions, true)"
                            />
                        @endforeach
                    </div>
                </x-pjutils::widget>
            @endforeach
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>
