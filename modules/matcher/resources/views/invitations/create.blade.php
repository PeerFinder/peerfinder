<x-matcher::layout.single :pg="$pg" edit="true">

    <x-ui.card title="{{ __('matcher::peergroup.send_group_invitation') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.invitations.create', ['pg' => $pg->groupname])">
            <x-ui.forms.section>
                <x-ui.forms.section-body>
                    <dropdown-input url="{{ route('profile.user.search') }}?name=$1"
                        input-name="search_users" :max-selected="0"
                        items-field="users" items-id="username"
                        items-value="name" :lookup-delay="500"
                        placeholder="{{ __('matcher::peergroup.invitation_enter_name') }}" :items="[]"
                        label="{{ __('matcher::peergroup.invitation_field_users') }}" :strict="true">

                    </dropdown-input>
                </x-ui.forms.section-body>

                <x-ui.forms.section-body>
                    <x-ui.forms.textarea id="comment" value="{{ old('comment') }}" name="comment" rows="3" required>{{ __('matcher::peergroup.invitation_comment') }}</x-ui.forms.textarea>
                </x-ui.forms.section-body>
            </x-ui.forms.section>

            <x-ui.forms.form-navigation>
                @method('PUT')
                <x-matcher::ui.edit-buttons :pg="$pg">@lang('matcher::peergroup.button_send_invitation')</x-matcher::ui.edit-buttons>
            </x-ui.forms.form-navigation>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>
