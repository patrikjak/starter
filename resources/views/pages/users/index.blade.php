@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Users\Role')
@use('Patrikjak\Starter\Models\Users\Permission')
@use('Patrikjak\Starter\Models\Users\User')

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.title')">

    <x-slot:actions>
        @can(BasePolicy::CREATE, User::class)
            <x-pjutils::button id="invite-user-btn">
                @lang('pjstarter::pages.users.invite_user')
            </x-pjutils::button>
        @endcan

        @can(BasePolicy::VIEW_ANY, Role::class)
            <x-pjutils::button href="{{ route('admin.users.roles.index') }}" :bordered="true">
                @lang('pjstarter::pages.users.roles.title')
            </x-pjutils::button>
        @endcan

        @can(BasePolicy::VIEW_ANY, Permission::class)
            <x-pjutils::button href="{{ route('admin.users.permissions.index') }}" :bordered="true">
                @lang('pjstarter::pages.users.permissions.title')
            </x-pjutils::button>
        @endcan
    </x-slot:actions>

    <div class="users">
        <x-pjutils.table::table :table="$usersTable" />
    </div>

    @can(BasePolicy::CREATE, User::class)
        <template id="invite-user-form">
            <x-pjutils::form action="{{ route('admin.api.users.invite') }}" method="POST" :action-label="null">
                <x-pjutils::form.input
                    name="email"
                    type="email"
                    :label="__('pjstarter::pages.users.email')"
                    autofocus
                />

                <x-pjutils::form.select
                    name="role_id"
                    :label="__('pjstarter::pages.users.role')"
                    :options="$roles->pluck('name', 'id')->toArray()"
                    :required="false"
                />
            </x-pjutils::form>
        </template>

        <script>
            document.getElementById('invite-user-btn').addEventListener('click', function () {
                const modal = new window.pjutils.Modal(true);
                modal.setTitle('{{ __('pjstarter::pages.users.invite_modal_title') }}');
                modal.setBody(document.getElementById('invite-user-form').innerHTML);
                modal.setFooterButton('{{ __('pjstarter::pages.users.invite_user') }}', function () {
                    const modalForm = document.querySelector('.modal .body form');
                    const form = new window.pjutils.Form(modalForm);

                    form.setSuccessCallback(function (f, response) {
                        window.pjutils.Form.defaultSuccessCallback(f, response);
                        modal.close();
                    });

                    form.submitWithButton(document.querySelector('.modal .footer .pj-btn'));

                    return false;
                });
                modal.open();
            });
        </script>
    @endcan

</x-pjstarter::layout.app>
