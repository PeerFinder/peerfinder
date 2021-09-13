<x-ui.card class="my-2 sm:my-5" title="{{ __('matcher::peergroup.group_description') }}">
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