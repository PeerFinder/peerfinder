@props(['group_request', 'cancel' => null])

<div class="space-y-2 sm:space-y-0 sm:space-x-2 flex sm:justify-between flex-col sm:flex-row">
    @if (strlen($slot))
    <x-ui.forms.button {{ $attributes }}>{{ $slot }}</x-ui.forms.button>
    @else
    <div></div>
    @endif
    @if ($group_request)
    <x-ui.forms.button tag="a" href="{{ $cancel ?: $group_request->getUrl() }}" action="inform">{{ __('group_requests::group_requests.button_cancel') }}</x-ui.forms.button>
    @else
    <x-ui.forms.button tag="a" href="{{ route('group_requests.index') }}" action="inform">{{ __('group_requests::group_requests.button_cancel') }}</x-ui.forms.button>
    @endif
</div>