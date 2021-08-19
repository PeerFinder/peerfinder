<x-talk::layout.single :conversation="$conversation">
    <x-ui.forms.form :action="route('talk.update', ['conversation' => $conversation->identifier])">
        <div>
            <x-ui.forms.input id="title" value="{{ old('title', $conversation->title) }}" name="title" type="text">{{ __('talk::talk.field_title') }}</x-ui.forms.input>
        </div>

        <div>
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('talk::talk.button_save_conversation') }}</x-ui.forms.button>
        </div>
    </x-ui.forms.form>
</x-talk::layout.single>