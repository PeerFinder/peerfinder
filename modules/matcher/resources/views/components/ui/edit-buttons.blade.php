@props(['pg', 'cancel' => null])

<div class="space-y-2 sm:space-y-0 sm:space-x-2 flex sm:justify-between flex-col sm:flex-row">
    @if (strlen($slot))
    <x-ui.forms.button {{ $attributes }}>{{ $slot }}</x-ui.forms.button>
    @else
    <div></div>
    @endif
    @if (!$pg->isDirty())
    <x-ui.forms.button tag="a" href="{{ $cancel ?: $pg->getUrl() }}" action="inform">{{ __('matcher::peergroup.button_cancel') }}</x-ui.forms.button>
    @else
    <x-ui.forms.button tag="a" href="{{ route('dashboard.index') }}" action="inform">{{ __('matcher::peergroup.button_cancel') }}</x-ui.forms.button>
    @endif
</div>