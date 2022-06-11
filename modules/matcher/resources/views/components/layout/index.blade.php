@props(['infocards' => null])

<x-layout.minimal :title="__('matcher::peergroup.peergroups_title')">
    @if ($infocards)
    <x-ui.top-infocards :infocards="$infocards" />
    @endif

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="px-4 sm:p-0 flex items-center justify-between">
            <x-ui.h1>{{ __('matcher::peergroup.peergroups_title') }}</x-ui.h1>
            <x-ui.forms.button tag="a" action="create" href="{{ route('matcher.create') }}">{{ __('matcher::peergroup.new_peergroup') }}</x-ui.forms.button>
        </div>

        {{ $slot }}
    </div>
</x-layout.minimal>