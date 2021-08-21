@props(['new' => false])

<div>
    <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="4">{{ __('talk::talk.field_message') }}</x-ui.forms.textarea>
</div>

<div>
    @csrf
    @method('PUT')
    @if ($new)
    <x-ui.forms.button action="create">{{ __('talk::talk.button_start_conversation') }}</x-ui.forms.button>
    @else
    <x-ui.forms.button>{{ __('talk::talk.button_send_reply') }}</x-ui.forms.button>
    @endif
</div>