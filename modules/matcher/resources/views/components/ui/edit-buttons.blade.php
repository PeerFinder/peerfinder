<div class="space-x-2 flex justify-between">
    <x-ui.forms.button>{{ $slot }}</x-ui.forms.button>
    <x-ui.forms.button tag="a" href="{{ $pg->getUrl() }}" action="inform">{{ __('matcher::peergroup.button_cancel') }}</x-ui.forms.button>
</div>