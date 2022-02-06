@if (session('success') || session('info') || session('error'))
<div class="sm:w-1/2 lg:w-1/3 mx-auto shadow-md bg-white sm:rounded-md mt-4 mb-4 sm:mb-0 flex items-stretch overflow-hidden">
    <div @class([
            'w-8 flex items-center justify-center',
            'bg-emerald-400' => session('success'),
            'bg-yellow-400' => session('info'),
            'bg-red-300' => session('error'),
        ])>
        @if (session('success'))
        <x-ui.icon name="check-circle" class="text-white" viewBox="0 0 20 20" />
        @elseif (session('info'))
        <x-ui.icon name="information-circle" class="text-white" viewBox="0 0 20 20" />
        @elseif (session('error'))
        <x-ui.icon name="exclamation" class="text-white" viewBox="0 0 20 20" />
        @endif
    </div>

    <div class="px-4 py-3 flex-1">
        {{ session('success') }}
        {{ session('info') }}
        {{ session('error') }}
    </div>
</div>
@endif

{{-- 
@if (session('success'))
    <x-ui.flash class="bg-emerald-200 border-emerald-500 p-3 border-y shadow text-center">
        <x-ui.icon name="check-circle" class="text-emerald-600" viewBox="0 2 20 20" /> {{ session('success') }}
    </x-ui.flash>
@endif

@if (session('info'))
    <x-ui.flash class="bg-yellow-100 border-yellow-300 p-3 border-y shadow text-center">
        <x-ui.icon name="information-circle" class="text-yellow-500" viewBox="0 2 20 20" /> {{ session('info') }}
    </x-ui.flash>
@endif

@if (session('error'))
    <x-ui.flash class="bg-red-300 border-red-500 p-3 border-y shadow text-center">
        <x-ui.icon name="exclamation" class="text-red-600" viewBox="0 1 20 20" /> {{ session('error') }}
    </x-ui.flash>
@endif --}}