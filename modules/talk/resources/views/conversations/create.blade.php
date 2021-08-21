<x-talk::layout.single>
    <p>Start conversation with {{ Talk::usersAsString(Talk::filterUsers($participants)) }}</p>

    <x-ui.forms.form :action="route('talk.store.user', ['user' => $participants[0]->username])">
        <x-talk::conversations.reply-form new="true" />
    </x-ui.forms.form>
</x-talk::layout.single>
