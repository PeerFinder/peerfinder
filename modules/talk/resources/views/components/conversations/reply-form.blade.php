@props(['new' => false])

<x-ui.errors :errors="$errors" class="p-3 mb-2" />

<div>
    <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="4" required>{{ __('talk::talk.field_message') }}</x-ui.forms.textarea>
</div>

<div class="mt-2">
    @csrf
    @method('PUT')
    @if ($new)
    <x-ui.forms.button action="create">{{ __('talk::talk.button_start_conversation') }}</x-ui.forms.button>
    @else
    <x-ui.forms.button>{{ __('talk::talk.button_send_reply') }}</x-ui.forms.button>
    @endif
</div>