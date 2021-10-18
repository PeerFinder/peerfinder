<div>
    @include('talk::partials.replies')

    <div class="mt-3">
        <x-talk::conversations.reply-form :conversation="$conversation" />
    </div>
</div>