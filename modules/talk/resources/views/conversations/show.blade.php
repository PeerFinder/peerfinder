<x-talk::layout.single :conversation="$conversation">
    @if ($conversation->title)
        <p>{{ $conversation->title }}</p>
    @endif

    <p>{{ Talk::usersAsString(Talk::filterUsersForConversation($conversation)) }}</p>

    @foreach ($replies as $reply)
        <p class="border mb-1 bg-yellow-300">{{ $reply->user->name }}: {{ $reply->message }}</p>
    @endforeach

    <x-ui.forms.form :action="route('talk.reply.store', ['conversation' => $conversation->identifier])">
        <x-talk::conversations.reply-form />
    </x-ui.forms.form>
</x-talk::layout.single>
