@use('Patrikjak\Utils\Common\Enums\Icon')
@use('Illuminate\Support\Facades\Vite')

<x-pjstarter::layout.app :title="__('pjstarter::pages.profile.password_change')">

    <div class="profile">
        <div class="card w-1/2 mx-auto">
            <p class="title">@lang('pjstarter::pages.profile.set_new_password')</p>
            
            <x-pjutils::form
                method="PATCH"
                :action="route('api.change-password')"
                :action-label="__('pjstarter::pages.profile.change_password')"
            >
                <x-pjutils::form.hidden name="validate_current_password" value="0" />

                <x-pjutils::form.password
                    name="password"
                    :label="__('pjauth::forms.password')"
                    :confirm-label="__('pjauth::forms.password_confirmation')"
                    :confirm="true"
                />
            </x-pjutils::form>
        </div>
    </div>

</x-pjstarter::layout.app>