@if ($conversations)
    <x-ui.card title="{{ __('matcher::peergroup.caption_conversations') }}" subtitle="{{ __('matcher::peergroup.visible_for_members') }}">
    @forelse ($conversations as $conversation)
        {{ Talk::embedConversation($conversation) }}
    @empty
        <p class="text-center p-4">{{ __('matcher::peergroup.no_conversations_yet') }}</p>
    @endforelse
    </x-ui.card>
@endif