@props(['conversations'])

@if ($conversations)
    <x-ui.card title="{{ __('matcher::peergroup.caption_conversations') }}">
    @forelse ($conversations as $conversation)
        <div class="p-4">
            {{ Talk::embedConversation($conversation) }}
        </div>
    @empty
        No conversations yet.
    @endforelse
    </x-ui.card>
@endif