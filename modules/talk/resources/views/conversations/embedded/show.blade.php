<div>
    @include('talk::components.conversations.replies')
    {{-- <x-talk::conversations.replies :replies="$replies" :conversation="$conversation" /> --}}

    <div class="mt-3">
        <x-talk::conversations.reply-form :conversation="$conversation" />
    </div>
</div>