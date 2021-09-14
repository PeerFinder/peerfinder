@props(['membership' => null])

<div class="p-4">
    <div>
        <x-ui.forms.textarea id="comment" value="{{ old('comment', $membership ? $membership->comment : '') }}" name="comment" rows="3">{{ __('matcher::peergroup.field_membership_comment') }}</x-ui.forms.textarea>
    </div>
</div>