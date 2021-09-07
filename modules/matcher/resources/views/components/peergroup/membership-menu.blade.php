<div class="my-5 text-center">
    @if ($pg->isMember())
    <x-ui.forms.button action="destroy" tag="a" href="{{ route('matcher.membership.delete', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_leave_group') }}</x-ui.forms.button>
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
</div>