<x-talk::layout.single>
    <p>Start conversation with {{ Talk::usersAsString(Talk::filterUsers($participants)) }}</p>

    <x-ui.forms.form :action="route('talk.store.user', ['user' => $participants[0]->username])">
        <div>
            <x-ui.forms.textarea id="message" value="{{ old('message') }}" name="message" rows="4">{{ __('talk::talk.field_message') }}</x-ui.forms.textarea>
        </div>

        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('talk::talk.button_send_message') }}</x-ui.forms.button>
        </div>
    </x-ui.forms.form>
</x-talk::layout.single>
