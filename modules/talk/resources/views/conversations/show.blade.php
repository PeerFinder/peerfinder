<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        @if ($conversation->title)
        <h2 class="font-bold">{{ $conversation->title }}</h2>
        @endif
    
        <p>{!! Talk::usersAsString(Talk::filterUsersForConversation($conversation), true) !!}</p>
    </div>

    <div class="p-6">
        <x-talk::conversations.replies :replies="$replies" />
    </div>

    <x-ui.forms.form :action="route('talk.reply.store', ['conversation' => $conversation->identifier])" class="p-4 border-t">
        <x-talk::conversations.reply-form />
    </x-ui.forms.form>
</x-talk::layout.single>