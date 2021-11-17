<div id="reply-{{ $reply->identifier }}">
    <div @class(["flex space-x-4", $highlighted_reply == $reply->identifier ? "bg-yellow-100 border border-yellow-200 p-2 rounded-md" : ""])>
        <div class="flex-shrink-0">
            @include('talk::partials.useravatar', ['user' => $reply->user, 'size' => $reply->reply_id ? 30 : 40])
        </div>
        
        <div class="flex-1 overflow-hidden">
            <div class="space-x-2 text-sm">
                @if ($reply->user_id)
                <a class="font-semibold inline-block" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }} <x-ui.user.awards :user="$reply->user" style="inline" /></a>
                @else
                <span class="font-semibold text-gray-400">{{ __('talk::talk.anonymous_user') }}</span>
                @endif
                <span class="inline-block text-xs text-gray-400">{{ Talk::formatDateTime($reply->created_at) }}</span>
            </div>

            <div class="font-serif font-light prose prose-blue">
                {!! Talk::renderReplyMessage($reply->message) !!}
            </div>

            <div class="edit-bar mt-1">
                <a @click.prevent="props.actionReply('{{ $reply->identifier }}')" href="#" class="text-sm inline-block text-pf-midblue hover:text-pf-lightblue">{{ __('talk::talk.button_reply_to_reply') }}</a>
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