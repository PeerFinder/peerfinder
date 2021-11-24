<x-matcher::layout.single :pg="$pg" edit="true">

    <x-ui.card title="{{ __('matcher::peergroup.edit_membership_roles_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.membership.manage', ['pg' => $pg->groupname])">
            @if ($pg->getMembers()->count() > 0)
            <div class="p-2 space-y-1 m-2">
                @foreach ($pg->memberships as $membership)
                    {{-- Don't show the user himself and the group owner --}}
                    @if ($membership->user)
                        <div class="border p-2 bg-gray-50 rounded-md shadow-sm lg:flex items-center gap-1 space-y-2 lg:space-y-0">
                            <div class="flex-1">
                                <x-matcher::ui.user :user="$membership->user" />
                            </div>
                            @if ($membership->user->id != $pg->user_id)
                            <div class="flex-1 flex space-x-2 items-center">
                                <div class="flex-1">
                                    <x-ui.forms.select name="roles[]" :options="$membership->memberRoles()" value="{{ old('roles.' . $loop->iteration, $membership->member_role_id) }}" />
                                    <input name="usernames[]" value="{{ $membership->user->username }}" type="hidden" />
                                </div>
                                <div>
                                    <x-ui.forms.button tag="a" action="destroy" href="{{ route('matcher.membership.delete', ['pg' => $pg->groupname, 'username' => $membership->user->username]) }}">{{ __('matcher::peergroup.button_remove') }}</x-ui.forms.button>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @else
            <p class="p-4">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</p>
            @endif

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg">
                @if ($pg->getMembers()->count() > 0)
                    {{ __('matcher::peergroup.button_change_membership_roles') }}
                @endif
                </x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>