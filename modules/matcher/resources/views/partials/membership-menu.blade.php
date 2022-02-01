<x-ui.card class="p-4 text-center">
    @if ($pg->isMember())
    <div class="space-y-5 text-center">
        <div class="text-center sm:flex-1">
            {{ __('matcher::peergroup.notice_member_of_group') }}
        </div>
        <div class="flex sm:inline-flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-1">
            <x-ui.forms.button action="inform" tag="a" href="{{ route('matcher.membership.edit', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_edit_membership') }}</x-ui.forms.button>
            <x-ui.forms.button action="destroy" tag="a" href="{{ route('matcher.membership.delete', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_leave_group') }}</x-ui.forms.button>
        </div>
    </div>
    @else
        @if ($pg->isPending())
        <x-ui.flash class="bg-yellow-100 p-3 border border-yellow-400 shadow text-center rounded-md">
            <x-ui.icon name="information-circle" class="text-yellow-500" /> {{ __('matcher::peergroup.request_pending_notice') }}
        </x-ui.flash>
        @elseif ($pg->isFull())
        <x-ui.flash class="bg-yellow-100 p-3 border border-yellow-400 shadow text-center rounded-md">
            <x-ui.icon name="information-circle" class="text-yellow-500" /> {{ __('matcher::peergroup.full_notice') }}
        </x-ui.flash>
        @elseif (!$pg->isOpen())
        <x-ui.flash class="bg-yellow-100 p-3 border border-yellow-400 shadow text-center rounded-md">
            <x-ui.icon name="information-circle" class="text-yellow-500" /> {{ __('matcher::peergroup.completed_notice') }}
        </x-ui.flash>
        @elseif($pg->needsApproval())
        <x-ui.forms.button tag="a" href="{{ route('matcher.membership.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_request_join_group') }}</x-ui.forms.button>
        @else
        <x-ui.forms.button tag="a" href="{{ route('matcher.membership.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_join_group') }}</x-ui.forms.button>
        @endif
    @endif
</x-ui.card>