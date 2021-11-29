@if ($replies->count())
@if ($embedded && $highlighted_reply)
<div class="text-center mb-5"><x-ui.forms.button tag="a" href="#reply-{{ $highlighted_reply }}" action="inform">{{ __('talk::talk.jump_to_last_reply') }}</x-ui.forms.button></div>
@endif

<div class="space-y-4">
    <conversation reply="{{ old('reply') }}" highlighted="{{ $embedded ? null : $highlighted_reply }}">
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
        <template v-slot:editing-form="props">
            <div class="border p-2 shadow-sm rounded-md">
                <x-talk::conversations.edit-form :conversation="$conversation" />
            </div>
        </template>        
    </conversation>
    
    {{ $replies->links('pagination::simple-tailwind') }}
</div>
@else
<p class="text-center py-4">{{ __('talk::talk.no_replies_yet') }}</p>
@endif