@props(['new' => false, 'reply' => false, 'conversation' => null, 'action' => null])

<x-ui.forms.form :action="$action ?: route('talk.replies.store', ['conversation' => $conversation->identifier])">

    <x-ui.forms.textarea id="reply-content" v-model="props.message" name="reply-content" rows="3" required>{{ __('talk::talk.field_edit_reply') }}</x-ui.forms.textarea>

    <div class="mt-2">
        @csrf
        @method('PUT')
        
        <input name="reply" type="hidden" v-bind:value="props.reply" />
        
        <div class="flex items-center md:justify-between flex-col md:flex-row space-y-1 md:space-y-0">
            <x-ui.forms.button vueClick="props.actionSave">{{ __('talk::talk.button_update_reply') }}</x-ui.forms.button>
            <x-ui.forms.button action="inform" vueClick="props.actionCancel">{{ __('talk::talk.button_cancel') }}</x-ui.forms.button>
        </div>
    </div>
</x-ui.forms.form>