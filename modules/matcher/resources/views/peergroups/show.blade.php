<x-matcher::layout.single :pg="$pg">

    <div class="my-5 text-center">
        @if ($pg->isMember())
            <x-ui.forms.button action="destroy">{{ __('matcher::peergroup.button_leave_group') }}</x-ui.forms.button>
        @else
            @if ($pg->needsApproval())
            <x-ui.forms.button>{{ __('matcher::peergroup.button_apply_membership') }}</x-ui.forms.button>
            @else
            <x-ui.forms.button>{{ __('matcher::peergroup.button_join_group') }}</x-ui.forms.button>
            @endif
        @endif
    </div>

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.group_description') }}">
        <div class="p-7 pb-4 space-x-2">
            <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
            @if ($pg->virtual)
            <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
            @else
            <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
            @endif
        </div>

        <div class="px-7 pb-7">
            {{ $pg->description }}
        </div>
    </x-ui.card>

    <x-matcher::peergroup.edit-menu :pg="$pg" />
</x-matcher::layout.single>