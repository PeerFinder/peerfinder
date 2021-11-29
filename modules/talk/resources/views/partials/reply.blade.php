<div id="reply-{{ $reply->identifier }}" class="bg-gray-50 rounded-md">
    <div @class(["flex space-x-4 p-2 rounded-md", $highlighted_reply == $reply->identifier ? "bg-yellow-100" : ""])>
        <div class="flex-shrink-0">
            @include('talk::partials.useravatar', ['user' => $reply->user, 'size' => $reply->reply_id ? 30 : 40])
        </div>
        
        <div class="flex-1 overflow-hidden">
            <div class="space-x-2 text-sm flex items-center">
                @if ($reply->user_id)
                <a class="font-semibold block" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }} <x-ui.user.awards :user="$reply->user" style="inline" /></a>
                @else
                <span class="font-semibold block text-gray-400">{{ __('talk::talk.anonymous_user') }}</span>
                @endif

                <span class="block text-gray-400">{{ Talk::formatDateTime($reply->created_at) }}</span>

                @can('edit', $reply)
                <a @click.prevent="props.actionEdit('{{ $reply->identifier }}')" href="#" title="{{ __('talk::talk.button_edit_reply') }}" class="text-gray-300 hover:text-gray-500 block"><x-ui.icon name="pencil-alt" size="h-4 w-4" viewBox="0 2 20 20" /></a>
                @endcan
            </div>

            <div class="content">
                <div class="font-serif font-light prose prose-blue" v-if="props.editing != '{{ $reply->identifier }}'">
                    {!! Talk::renderReplyMessage($reply->message) !!}
                </div>
            </div>

            <div class="edit-bar mt-1">
                <a @click.prevent="props.actionReply('{{ $reply->identifier }}')" href="#" class="text-sm inline-block text-pf-midblue hover:text-pf-lightblue">{{ __('talk::talk.button_reply_to_reply') }}</a>
            </div>
            
            @if ($reply->replies->count())
            <div class="mt-3 space-y-2">
                @foreach ($reply->replies as $sub_reply)
                    @include('talk::partials.reply', ['reply' => $sub_reply])
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>