<x-pjstarter::layout.app :title="sprintf('%s - %s', __('pjstarter::pages.users.roles.manage_permissions'), $role->name)">

    <div class="manage-permissions">
        <x-pjutils::form
            method="PUT"
            :action="route('api.users.roles.permissions', ['role' => $role->id])"
            :action-label="__('pjstarter::general.save')"
        >
            @foreach($availablePermissions as $feature => $permissions)
                <div class="permission-group mb-2%">
                    <p class="strong font-primary">{{ ucfirst($feature) }}</p>

                    <div class="permissions grid-4-items mt-2">
                        @foreach($permissions as $permission)
                            <x-pjutils::form.checkbox
                                :name="sprintf('permission_%s', $permission->name)"
                                :label="$permission->description"
                                :checked="in_array($permission->name, $assignedPermissions, true)"
                            />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </x-pjutils::form>
    </div>

</x-pjstarter::layout.app>