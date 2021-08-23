<div>
    <x-talk::conversations.replies :replies="$replies" />

    <x-ui.forms.form :action="route('talk.reply.store', ['conversation' => $conversation->identifier])" class="mt-3">
        <x-talk::conversations.reply-form />
    </x-ui.forms.form>
</div>