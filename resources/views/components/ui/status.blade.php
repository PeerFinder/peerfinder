@if (session('success'))
    <x-ui.flash class="bg-green-200 border-green-500 p-3 border-b border-green-600 shadow text-center">
        <x-ui.icon name="check-circle" class="text-green-600" viewBox="0 2 20 20" /> {{ session('success') }}
    </x-ui.flash>
@endif

@if (session('info'))
    <x-ui.flash class="bg-yellow-100 border-yellow-300 p-3 border-b border-yellow-400 shadow text-center">
        <x-ui.icon name="information-circle" class="text-yellow-500" viewBox="0 2 20 20" /> {{ session('info') }}
    </x-ui.flash>
@endif

@if (session('error'))
    <x-ui.flash class="bg-red-300 border-red-500 p-3 border-b border-red-600 shadow text-center">
        <x-ui.icon name="exclamation" class="text-red-600" viewBox="0 1 20 20" /> {{ session('error') }}
    </x-ui.flash>
@endif