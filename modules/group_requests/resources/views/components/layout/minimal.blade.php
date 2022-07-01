<x-layout.minimal :title="$title">
    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="px-4 sm:p-0 flex items-center justify-between">
            <x-ui.h1>{{ $title }}</x-ui.h1>
            <x-ui.forms.button tag="a" action="create" href="{{ route('group_requests.create') }}">{{ __('group_requests::group_requests.create_request') }}</x-ui.forms.button>
        </div>
        
        {{ $slot }}
    </div>
</x-layout.minimal>