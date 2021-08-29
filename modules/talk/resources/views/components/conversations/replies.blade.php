<div class="space-y-4">

{{ $replies->links('talk::components.ui.pagination') }}

@foreach ($replies as $reply)
    <div id="reply-{{ $reply->identifier }}">
        <div class="flex justify-between mb-1 text-sm">
            <div class="flex items-center">
                <x-ui.user.avatar :user="$reply->user" size="20" class="rounded-full inline-block mr-1" />
                <a class="font-semibold" href="{{ $reply->user->profileUrl() }}">{{ $reply->user->name }}</a>
            </div>
            <span class="inline-block mt-1 text-xs text-gray-300">{{ Talk::formatDateTime($reply->created_at) }}</span>
        </div>
        <div class="border border-gray-200 mb-1 bg-gray-50 rounded-md px-3 py-2 shadow-sm">
            {{ $reply->message }}
        </div>
    </div>
@endforeach

{{ $replies->links('talk::components.ui.pagination') }}

</div>