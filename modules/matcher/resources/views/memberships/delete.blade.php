<x-matcher::layout.single :pg="$pg">

    <x-ui.card title="{{ $is_self ? __('matcher::peergroup.leave_group_title') : __('matcher::peergroup.remove_member_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.membership.destroy', ['pg' => $pg->groupname, 'username' => $user->username])">
            <p class="p-4">{{ $is_self ? __('matcher::peergroup.leave_group_notice') : __('matcher::peergroup.remove_member_notice', ['name' => $user->name]) }}</p>

            @if (!$is_self)
            <div class="p-4 pt-0">
                <x-matcher::ui.user :user="$user" />
            </div>
            @endif

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('DELETE')

                @if ($is_self)
                <x-matcher::ui.edit-buttons action="destroy" :pg="$pg">{{ __('matcher::peergroup.button_leave_group') }}</x-matcher::ui.edit-buttons>
                @else
                <x-matcher::ui.edit-buttons action="destroy" cancel="{{ route('matcher.membership.index', ['pg' => $pg->groupname]) }}" :pg="$pg">{{ __('matcher::peergroup.button_remove_member') }}</x-matcher::ui.edit-buttons>
                @endif
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>