<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.join_group_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.membership.store', ['pg' => $pg->groupname])">
            

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')

                @if($pg->needsApproval())
                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_request_join_group') }}</x-matcher::ui.edit-buttons>
                @else
                <x-matcher::ui.edit-buttons :pg="$pg">{{ __('matcher::peergroup.button_join_group') }}</x-matcher::ui.edit-buttons>
                @endif
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>