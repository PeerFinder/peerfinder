<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        @if ($conversation->isOwnerPeergroup())
        <div class="text-xs flex items-center mb-2">
            <div class="mr-1 pb-0.5">
                <x-ui.icon name="user-group" size="w-3 h-3" />
            </div>
            <div class="line-clamp-1"><x-ui.link href="{{ $conversation->conversationable->getUrl() }}">{{ $conversation->conversationable->title }}</x-ui.link></div>
        </div>
        @endif

        @if ($conversation->isOwnerGroupRequest())
        <div class="text-xs flex items-center mb-2">
            <div class="line-clamp-1"><x-ui.link href="{{ $conversation->conversationable->getUrl() }}">{{ $conversation->conversationable->title }}</x-ui.link></div>
        </div>
        @endif

        @if ($conversation->title)
        <h2 class="font-semibold">{{ $conversation->title }}</h2>
        @endif

        <p>{!! Talk::usersAsString(Talk::filterUsersForConversation($conversation), true) !!}</p>
    </div>

    <div class="p-4">
        @include('talk::partials.replies', ['embedded' => false])
    </div>

    <x-talk::conversations.reply-form :conversation="$conversation" />

    <x-talk::ui.join-nav :conversation="$conversation" />
</x-talk::layout.single>