<x-talk::layout.single :conversation="$conversation">
    <div class="p-4 border-b">
        <p>{{ __('talk::talk.edit_conversation') }}</p>
    </div>

    <x-ui.forms.form :action="route('talk.update', ['conversation' => $conversation->identifier])" class="p-4">
        <div>
            <x-ui.forms.input id="title" value="{{ old('title', $conversation->title) }}" name="title" type="text">{{ __('talk::talk.field_title') }}</x-ui.forms.input>
        </div>

        <div class="mt-2">
            @csrf
            @method('PUT')
            <x-ui.forms.button>{{ __('talk::talk.button_save_conversation') }}</x-ui.forms.button>
        </div>
    </x-ui.forms.form>
</x-talk::layout.single>