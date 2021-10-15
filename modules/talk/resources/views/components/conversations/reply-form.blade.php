@props(['new' => false, 'reply' => false, 'conversation' => null, 'action' => null])

<x-ui.forms.form :action="$action ?: route('talk.reply.store', ['conversation' => $conversation->identifier])">

    <x-ui.errors :errors="$errors" class="p-3 mb-2" />

    <div>
        <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="3" required>
            @if ($reply)
            {{ __('talk::talk.field_reply') }}
            @else
            {{ __('talk::talk.field_message') }}
            @endif
        </x-ui.forms.textarea>
    </div>

    <div class="mt-2">
        @csrf
        @method('PUT')
        @if ($reply)
        <input name="reply" type="text" v-bind:value="props.reply" />
        @endif
        @if ($new)
        <x-ui.forms.button action="create">{{ __('talk::talk.button_start_conversation') }}</x-ui.forms.button>
        @else
        <x-ui.forms.button>{{ __('talk::talk.button_send_reply') }}</x-ui.forms.button>
        @endif
    </div>

</x-ui.forms.form>