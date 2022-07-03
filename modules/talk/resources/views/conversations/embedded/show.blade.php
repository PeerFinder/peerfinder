<div class="p-4">
    @include('talk::partials.replies', ['embedded' => true])
</div>

<x-talk::conversations.reply-form :conversation="$conversation" />

<x-talk::ui.join-nav :conversation="$conversation" />