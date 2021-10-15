@props(['replies', 'reply' => null, 'conversation'])

<div class="space-y-4">
    {{ $replies->links('talk::components.ui.pagination') }}

    <div class="space-y-6">
        @forelse ($replies->reverse() as $reply)
        <x-talk::conversations.reply :reply="$reply" :conversation="$conversation" />
        @empty
        <p class="text-center">{{ __('talk::talk.no_replies_yet') }}</p>
        @endforelse
    </div>

    {{--
    
        <div class="bg-yellow-200">
                <x-talk::conversations.reply-form :reply="$reply" :conversation="$conversation" />
            </div>
    --}}

    {{ $replies->links('talk::components.ui.pagination') }}
</div>