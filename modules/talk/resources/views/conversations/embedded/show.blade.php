<div class="p-4">
    @include('talk::partials.replies')
</div>

<div class="border-t">
    <div class="p-4">
        <x-talk::conversations.reply-form :conversation="$conversation" />
    </div>
</div>