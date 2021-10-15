<div class="space-y-4">
    {{ $replies->links('talk::components.ui.pagination') }}

    <div class="space-y-6">
        @forelse ($replies->reverse() as $reply)
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
                    <div class="prose prose-blue">
                        {!! Talk::renderReplyMessage($reply->message) !!}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">{{ __('talk::talk.no_replies_yet') }}</p>
        @endforelse
    </div>

    {{ $replies->links('talk::components.ui.pagination') }}
</div>