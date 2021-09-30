@can('edit', $pg)
<x-ui.card class="after:bg-gradient-to-r after:from-red-400 after:to-red-600 after:h-1 after:block overflow-hidden" title="{{ __('matcher::peergroup.group_administration') }}" subtitle="{{ __('matcher::peergroup.group_administration_notice') }}">
    <div class="p-1 sm:p-4 space-y-2 flex flex-col">
        <x-ui.forms.form :action="route('matcher.complete', ['pg' => $pg->groupname])" method="post">
            @csrf
            @if ($pg->isOpen())
            <input name="status" value="1" type="hidden" />
            <x-ui.forms.button action="inform" class="w-full">{{ __('matcher::peergroup.button_complete_group') }}</x-ui.forms.button>
            @else
            <input name="status" value="0" type="hidden" />
            @if ($pg->canUncomplete())
            <x-ui.forms.button action="create" class="w-full">{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @else
            <x-ui.forms.button action="inform" class="w-full text-gray-300 border-gray-200" disabled>{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @endif
            @endif
        </x-ui.forms.form>

        <x-ui.forms.button tag="a" href="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_group') }}</x-ui.forms.button>
        
        <x-ui.forms.button tag="a" href="{{ route('matcher.bookmarks.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_bookmarks') }}</x-ui.forms.button>
        
        <x-ui.forms.button tag="a" href="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_appointments') }}</x-ui.forms.button>

        @if ($pg->hasMoreMembersThanOwner())
        <x-ui.forms.button tag="a" href="{{ route('matcher.editOwner', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_transfer_ownership') }}</x-ui.forms.button>
        @endif

        <x-ui.forms.button tag="a" href="{{ route('matcher.delete', ['pg' => $pg->groupname]) }}" action="destroy">{{ __('matcher::peergroup.button_delete_group') }}</x-ui.forms.button>
    </div>
</x-ui.card>
@endcan
