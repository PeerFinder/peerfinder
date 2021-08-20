<div>
    <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="4">{{ __('talk::talk.field_message') }}</x-ui.forms.textarea>
</div>

<div>
    @csrf
    @method('PUT')
    <x-ui.forms.button>{{ __('talk::talk.button_send_message') }}</x-ui.forms.button>
</div>