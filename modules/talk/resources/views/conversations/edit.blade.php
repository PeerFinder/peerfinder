<x-talk::layout.single :conversation="$conversation">

    <div>
        <x-ui.forms.input id="title" value="{{ old('title', $conversation->title) }}" name="title" type="text">{{ __('talk::talk.field_title') }}</x-ui.forms.input>
    </div>

</x-talk::layout.single>