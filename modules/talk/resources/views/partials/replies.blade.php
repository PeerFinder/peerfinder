<div class="space-y-4">
    {{ $replies->links('talk::components.ui.pagination') }}

    <conversation reply="{{ old('reply') }}">
        <template v-slot:replies="props">
            @forelse ($replies->reverse() as $reply)
            @include('talk::partials.reply')
            @empty
            <p class="text-center">{{ __('talk::talk.no_replies_yet') }}</p>
            @endforelse
        </template>
        <template v-slot:reply-form="props">
            <div class="bg-white border shadow-sm p-2 mt-1 rounded-md">
                <x-talk::conversations.reply-form reply="true" :conversation="$conversation" />
            </div>
        </template>
    </conversation>

    {{ $replies->links('talk::components.ui.pagination') }}
</div>