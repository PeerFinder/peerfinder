<x-matcher::layout.single :pg="$pg" edit="true">

    <x-ui.card title="{{ __('matcher::peergroup.send_group_invitation') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.invitations.create', ['pg' => $pg->groupname])">
            
            <p class="p-4">
                <dropdown-input url="{{ route('profile.user.search') }}?name=$1" 
                            input-name="search_users" :max-selected="0" 
                            items-field="users" items-id="username" 
                            items-value="name" :lookup-delay="500"
                            placeholder="{{ __('matcher::peergroup.invitation_enter_name') }}" :items=""
                            label="{{ __('matcher::peergroup.invitation_field_users') }}" :strict="true">

                </dropdown-input>
            </p>

            <p class="p-4 pt-0">
                <x-ui.forms.textarea id="comment" value="{{ old('comment') }}" name="comment" rows="3">{{ __('matcher::peergroup.invitation_comment') }}</x-ui.forms.textarea>
            </p>

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')
                <x-matcher::ui.edit-buttons :pg="$pg">@lang('matcher::peergroup.button_send_invitation')</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>
