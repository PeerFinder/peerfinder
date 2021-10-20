@props(['new' => false, 'reply' => false, 'conversation' => null, 'action' => null])

<x-ui.forms.form :action="$action ?: route('talk.reply.store', ['conversation' => $conversation->identifier])">

    <x-ui.errors :errors="$errors" class="p-3 mb-2" />

    <div>
        @if ($reply)
        <x-ui.forms.textarea id="reply_message" value="{{ old('reply_message') }}" name="reply_message" rows="3" required>{{ __('talk::talk.field_reply') }}</x-ui.forms.textarea>
        @else
        <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="3" required>{{ __('talk::talk.field_message') }}</x-ui.forms.textarea>
        @endif
    </div>

    <div class="mt-2">
        @csrf
        @method('PUT')
        @if ($reply)
        <input name="reply" type="hidden" v-bind:value="props.reply" />
        @endif
        @if ($new)
        <x-ui.forms.button action="create">{{ __('talk::talk.button_start_conversation') }}</x-ui.forms.button>
        @else
        <x-ui.forms.button>{{ __('talk::talk.button_send_reply') }}</x-ui.forms.button>
        @endif
    </div>

</x-ui.forms.form>