<x-matcher::layout.single :pg="$pg">

    
    <div class="my-5 text-center">
        @if ($pg->isMember())
        <x-ui.forms.button action="destroy">{{ __('matcher::peergroup.button_leave_group') }}</x-ui.forms.button>
        @else
            @if ($pg->isPending())
            Your request is pending.
            @elseif($pg->needsApproval())
            <x-ui.forms.button tag="a" href="{{ route('matcher.membership.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_request_join_group') }}</x-ui.forms.button>
            @else
            <x-ui.forms.button tag="a" href="{{ route('matcher.membership.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_join_group') }}</x-ui.forms.button>
            @endif
        @endif
    </div>
    

    @if ($pg->isFull())
    <x-ui.flash class="bg-yellow-100 p-3 border border-yellow-400 shadow text-center rounded-md">
        <x-ui.icon name="information-circle" class="text-yellow-500" /> {{ __('matcher::peergroup.full_notice') }}
    </x-ui.flash>
    @elseif (!$pg->isOpen())
    <x-ui.flash class="bg-yellow-100 p-3 border border-yellow-400 shadow text-center rounded-md">
        <x-ui.icon name="information-circle" class="text-yellow-500" /> {{ __('matcher::peergroup.completed_notice') }}
    </x-ui.flash>
    @endif
    

    <x-matcher::peergroup.edit-menu :pg="$pg" />

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.group_description') }}">
        <div class="p-7 pb-4">
            <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
            @if ($pg->virtual)
            <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
            @else
            <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
            @endif
            <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $pg->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
        </div>

        <div class="px-7 pb-7">
            {{ $pg->description }}
        </div>
    </x-ui.card>
</x-matcher::layout.single>