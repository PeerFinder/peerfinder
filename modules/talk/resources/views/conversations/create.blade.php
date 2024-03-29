<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.start_conversation_with', ['participants' => Talk::usersAsString(Talk::filterUsers($participants))]) }}</p>
    </div>

    <x-talk::conversations.reply-form new="true" :action="route('talk.store.user', ['usernames' => $participantsString])" />
</x-talk::layout.single>