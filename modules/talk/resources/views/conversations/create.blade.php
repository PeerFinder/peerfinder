<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.start_conversation_with', ['participants' => Talk::usersAsString(Talk::filterUsers($participants))]) }}</p>
    </div>

    <x-ui.forms.form :action="route('talk.store.user', ['user' => $participants[0]->username])" class="p-4">
        <x-talk::conversations.reply-form new="true" />
    </x-ui.forms.form>
</x-talk::layout.single>