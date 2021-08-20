<x-talk::layout.single :conversation="$conversation">
    @if ($conversation->title)
        <p>{{ $conversation->title }}</p>
    @endif

    <p>{{ Talk::usersAsString(Talk::filterUsersForConversation($conversation)) }}</p>
</x-talk::layout.single>
