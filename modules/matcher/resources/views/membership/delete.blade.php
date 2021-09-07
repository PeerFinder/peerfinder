<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.leave_group_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.membership.destroy', ['pg' => $pg->groupname])">
            <p class="p-4">{{ __('matcher::peergroup.leave_group_notice') }}</p>

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('DELETE')

                <x-matcher::ui.edit-buttons action="destroy" :pg="$pg">{{ __('matcher::peergroup.button_leave_group') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>