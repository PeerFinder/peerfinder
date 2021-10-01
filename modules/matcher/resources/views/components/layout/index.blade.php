<x-layout.minimal :title="__('matcher::peergroup.peergroups_title')">

    <div class="mt-5 px-4 sm:p-0 flex items-center justify-between">
        <h1 class="text-3xl font-semibold">{{ __('matcher::peergroup.peergroups_title') }}</h1>
        <x-ui.forms.button tag="a" action="create" href="{{ route('matcher.create') }}">{{ __('matcher::peergroup.new_peergroup') }}</x-ui.forms.button>
    </div>

    {{ $slot }}
    
</x-layout.minimal>