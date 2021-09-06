<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.delete_group') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.delete', ['pg' => $pg->groupname])">
            <div class="p-4 space-y-4">
                <p>{{ __('matcher::peergroup.delete_group_notice') }}</p>

                @if ($pg->hasMoreMembersThanOwner())
                <p class="text-yellow-500">
                    <x-ui.icon name="exclamation" />{!! __('matcher::peergroup.delete_group_has_members_notice', ['link' => htmlspecialchars(route('matcher.editOwner', ['pg' => $pg->groupname]))]) !!}
                </p>
                @endif
            </div>

            <div class="p-4">
                <x-ui.forms.checkbox id="confirm_delete" name="confirm_delete">{{ __('matcher::peergroup.delete_group_confirm') }}</x-ui.forms.checkbox>
            </div>
            
            <div class="mt-2 p-4 border-t">
                @csrf
                @method('DELETE')
                <x-matcher::ui.edit-buttons action="destroy" :pg="$pg">{{ __('matcher::peergroup.button_delete_group') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>