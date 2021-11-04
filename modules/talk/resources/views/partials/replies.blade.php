@if ($replies->count())
<div class="space-y-4">
    <conversation reply="{{ old('reply') }}">
        <template v-slot:replies="props">
            @foreach ($replies->reverse() as $reply)
                @include('talk::partials.reply')
            @endforeach
        </template>
        <template v-slot:reply-form="props">
            <div class="bg-white border shadow-sm p-2 mt-1 rounded-md">
                <x-talk::conversations.reply-form reply="true" :conversation="$conversation" />
            </div>
        </template>
    </conversation>
    
    {{ $replies->links('pagination::simple-tailwind') }}
</div>
@else
<p class="text-center py-4">{{ __('talk::talk.no_replies_yet') }}</p>
@endif