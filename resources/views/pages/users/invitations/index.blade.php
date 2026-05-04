@use('Patrikjak\Starter\Policies\BasePolicy')
@use('Patrikjak\Starter\Models\Users\User')

<x-pjstarter::layout.app :title="__('pjstarter::pages.users.invitations.title')">

    <div class="invitations">
        <x-pjutils.table::table :table="$invitationsTable" />
    </div>

    @can(BasePolicy::CREATE, User::class)
        <template id="change-role-form">
            <x-pjutils::form :action-label="null">
                <x-pjutils::form.select
                    name="role_id"
                    :label="__('pjstarter::pages.users.role')"
                    :options="$roles->pluck('name', 'id')->toArray()"
                    :required="false"
                />
            </x-pjutils::form>
        </template>

        <script>
            document.querySelectorAll('.pj-table-wrapper').forEach(function (tableWrapper) {
                tableWrapper.addEventListener('click', function (event) {
                    const actionBtn = event.target.closest('.action-btn.change-role');

                    if (!actionBtn) {
                        return;
                    }

                    const row = actionBtn.closest('tr');
                    const email = row ? row.id : null;

                    if (!email) {
                        return;
                    }

                    const modal = new window.pjutils.Modal(true);
                    modal.setTitle('{{ __('pjstarter::pages.users.invitations.change_role_modal_title') }}');
                    modal.setBody(document.getElementById('change-role-form').innerHTML);
                    modal.setFooterButton('{{ __('pjstarter::pages.users.invitations.change_role') }}', function () {
                        const modalForm = document.querySelector('.modal .body form');
                        modalForm.setAttribute('action', '{{ route('admin.api.users.invitations.update', ['email' => '__EMAIL__']) }}'.replace('__EMAIL__', encodeURIComponent(email)));
                        modalForm.setAttribute('method', 'PUT');

                        const form = new window.pjutils.Form(modalForm);

                        form.setSuccessCallback(function (submittedForm, response) {
                            window.pjutils.Form.defaultSuccessCallback(submittedForm, response);
                            modal.close();
                        });

                        form.submitWithButton(document.querySelector('.modal .footer .pj-btn'));

                        return false;
                    });
                    modal.open();
                });
            });
        </script>
    @endcan

</x-pjstarter::layout.app>
