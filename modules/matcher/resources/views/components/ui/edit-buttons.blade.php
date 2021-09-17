@props(['pg'])

<div class="space-x-2 flex justify-between">
    <x-ui.forms.button {{ $attributes }}>{{ $slot }}</x-ui.forms.button>
    @if (!$pg->isDirty())
    <x-ui.forms.button tag="a" href="{{ $pg->getUrl() }}" action="inform">{{ __('matcher::peergroup.button_cancel') }}</x-ui.forms.button>
    @endif
</div>