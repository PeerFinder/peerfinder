@if ($pg && $pg->virtual && $pg->meeting_link && $pg->isMember())
<x-ui.card title="{{ __('matcher::peergroup.caption_meeting_link') }}" subtitle="{{ __('matcher::peergroup.visible_for_members') }}"  edit="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="p-4">
        <x-ui.link href="{{ $pg->meeting_link }}" target="_blank" class="block truncate">{{ $pg->meeting_link }}</x-ui.link>
    </div>
</x-ui.card>
@endif