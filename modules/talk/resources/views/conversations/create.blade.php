<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.start_conversation_with', ['participants' => Talk::usersAsString(Talk::filterUsers($participants))]) }}</p>
    </div>

    <div class="p-4">
        <x-talk::conversations.reply-form new="true" :action="route('talk.store.user', ['user' => $participants[0]->username])" />
    </div>
</x-talk::layout.single>