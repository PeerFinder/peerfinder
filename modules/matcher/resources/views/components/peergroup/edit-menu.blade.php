@can('edit', $pg)
<x-ui.card class="my-5" title="{{ __('matcher::peergroup.group_administration') }}" subtitle="{{ __('matcher::peergroup.group_administration_notice') }}">
    <div class="flex justify-between p-4">
        <x-ui.forms.button tag="a" href="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_group') }}</x-ui.forms.button>
        <x-ui.forms.button tag="a" href="{{ route('matcher.editCompleted', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_complete_group') }}</x-ui.forms.button>
        <x-ui.forms.button tag="a" href="{{ route('matcher.editOwner', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_transfer_ownership') }}</x-ui.forms.button>
        <x-ui.forms.button tag="a" href="{{ route('matcher.delete', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_delete_group') }}</x-ui.forms.button>
    </div>
</x-ui.card>
@endcan