@if ($pg && $pg->virtual && $pg->meeting_link && $pg->isMember())
<x-ui.card title="{{ __('matcher::peergroup.caption_meeting_link') }}">
    <div class="p-4">
        <p class="mb-2 text-sm text-gray-600">{{ __('matcher::peergroup.this_link_visible_for_members') }}</p>
        <x-ui.link href="{{ $pg->meeting_link }}" target="_blank" class="block truncate">{{ $pg->meeting_link }}</x-ui.link>
    </div>
</x-ui.card>
@endif