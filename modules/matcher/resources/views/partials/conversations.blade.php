@if ($conversations)
    <x-ui.card title="{{ __('matcher::peergroup.caption_conversations') }}">
    @forelse ($conversations as $conversation)
        <div class="p-4">
            {{ Talk::embedConversation($conversation) }}
        </div>
    @empty
        <p class="text-center p-4">{{ __('matcher::peergroup.no_conversations_yet') }}</p>
    @endforelse
    </x-ui.card>
@endif