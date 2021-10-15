<div id="reply-{{ $reply->identifier }}">
    <div class="flex space-x-3">
        <div>
            <x-ui.user.avatar :user="$reply->user" size="40" class="rounded-full inline-block" />
        </div>
        <div class="flex-1">
            <div class="space-x-2 text-sm">
                <a class="font-semibold inline-block" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }}</a>
                <span class="inline-block text-xs text-gray-400">{{ Talk::formatDateTime($reply->created_at) }}</span>
            </div>

            <div class="prose prose-blue bg-green-200">
                {!! Talk::renderReplyMessage($reply->message) !!}
            </div>

            @if ($reply->replies->count())
            <div class="mt-3 bg-red-200 border space-y-2">
                @foreach ($reply->replies as $sub_reply)
                <x-talk::conversations.reply :reply="$sub_reply" :conversation="$conversation" />
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>