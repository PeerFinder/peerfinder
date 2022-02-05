<div id="reply-{{ $reply->identifier }}" class="relative">
    @if ($reply->reply_id)
    <a href="#reply-{{ $reply->identifier }}" class="block bg-gray-100 hover:bg-gray-300 w-1 left-[0.8rem] top-9 bottom-0 absolute rounded-full"><span class="sr-only">Jump to comment</span></a>
    @else
    <a href="#reply-{{ $reply->identifier }}" class="block bg-gray-100 hover:bg-gray-300 w-1 left-[1.1rem] top-12 bottom-0 absolute rounded-full"><span class="sr-only">Jump to comment</span></a>
    @endif
    
    <div class="flex space-x-2 rounded-md">
        <div class="shrink-0">
            @include('talk::partials.useravatar', ['user' => $reply->user, 'size' => $reply->reply_id ? 30 : 40])
        </div>
        
        <div @class(["flex-1 overflow-hidden rounded-md mb-2", $highlighted_reply == $reply->identifier ? "bg-yellow-100 p-3" : ""])>
            <div class="space-x-2 text-sm flex items-center">
                @if ($reply->user_id)
                <a class="font-semibold block" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }} <x-ui.user.awards :user="$reply->user" style="inline" /></a>
                @else
                <span class="font-semibold block text-gray-400">{{ __('talk::talk.anonymous_user') }}</span>
                @endif

                <span class="block text-gray-400">{{ Talk::formatDateTime($reply->created_at) }}</span>

                @can('edit', [$reply, $conversation])
                <div id="edit-bar-{{ $reply->identifier }}" class="flex space-x-1 items-center">
                    <a @click.prevent="props.actionEdit('{{ $reply->identifier }}')" href="#" title="{{ __('talk::talk.button_edit_reply') }}" class="text-gray-300 hover:text-gray-500 block"><x-ui.icon name="pencil-alt" size="h-4 w-4" viewBox="0 2 20 20" /></a>
                </div>
                @endcan
            </div>

            <div class="content">
                <div v-if="props.editing != '{{ $reply->identifier }}'">
                    <div v-if="!props.updates['{{ $reply->identifier }}']" class="font-serif font-light prose prose-blue">
                        {!! Talk::renderReplyMessage($reply->message) !!}
                    </div>
                    <div v-if="props.updates['{{ $reply->identifier }}']" v-html="props.updates['{{ $reply->identifier }}']" class="font-serif font-light prose prose-blue"></div>
                </div>
            </div>

            @if ($level < 2)
            <div class="edit-bar mt-1">
                <a @click.prevent="props.actionReply('{{ $reply->identifier }}')" href="#" class="text-sm inline-block text-pf-midblue hover:text-pf-lightblue">{{ __('talk::talk.button_reply_to_reply') }}</a>
            </div>
            @endif
            
            @if ($reply->replies->count())
            <div class="mt-3 space-y-2">
                @foreach ($reply->replies as $sub_reply)
                    @include('talk::partials.reply', ['reply' => $sub_reply, 'level' => $level + 1])
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>