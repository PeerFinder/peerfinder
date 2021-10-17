{{-- @props(['replies', 'reply' => null, 'conversation']) --}}

<div class="space-y-4">
    {{ $replies->links('talk::components.ui.pagination') }}

    <conversation>
        <template v-slot:replies="props">
            @forelse ($replies->reverse() as $reply)
            @include('talk::partials.reply')
            {{-- <x-talk::conversations.reply :reply="$reply" :conversation="$conversation" /> --}}
            @empty
            <p class="text-center">{{ __('talk::talk.no_replies_yet') }}</p>
            @endforelse
        </template>
        <template v-slot:reply-form="props">
            <div class="bg-yellow-200">
                <x-talk::conversations.reply-form reply="true" :conversation="$conversation" />
            </div>
        </template>
    </conversation>

    {{ $replies->links('talk::components.ui.pagination') }}
</div>