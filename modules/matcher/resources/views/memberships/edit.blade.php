<x-matcher::layout.single :pg="$pg" edit="true">

    <x-ui.card title="{{ __('matcher::peergroup.update_membership_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.membership.update', ['pg' => $pg->groupname])">
            <x-matcher::membership.edit :membership="$membership" />

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_update_membership') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>