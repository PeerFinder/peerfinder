<div id="reply-{{ $reply->identifier }}">
    <div class="flex space-x-3">
        <div>
            @if ($reply->reply_id)
                @include('talk::partials.useravatar', ['user' => $reply->user, 'size' => 30]) 
            @else
                @include('talk::partials.useravatar', ['user' => $reply->user, 'size' => 40]) 
            @endif
        </div>
        <div class="flex-1">
            <div class="space-x-2 text-sm">
                <a class="font-semibold inline-block" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }}</a>
                <span class="inline-block text-xs text-gray-400">{{ Talk::formatDateTime($reply->created_at) }}</span>
            </div>

            <div class="prose prose-blue">
                {!! Talk::renderReplyMessage($reply->message) !!}
            </div>

            <div class="mt-1">
                <a @click.prevent="props.actionReply('{{ $reply->identifier }}')" href="#" class="text-sm inline-block px-2 bg-gray-100 rounded-md hover:bg-pf-midblue hover:text-white">Reply</a>
            </div>

            @if ($reply->replies->count())
            <div class="mt-3 space-y-2 border bg-gray-50 p-2 rounded-md">
                @foreach ($reply->replies as $sub_reply)
                    @include('talk::partials.reply', ['reply' => $sub_reply])
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>