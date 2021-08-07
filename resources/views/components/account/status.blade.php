@if (session('success'))
    <x-account.flash class="bg-green-200 border-green-500">
        <x-ui.icon name="check-circle" class="text-green-600" /> {{ session('success') }}
    </x-account.flash>
@endif

@if (session('error'))
    <x-account.flash class="bg-red-300 border-red-500">
        <x-ui.icon name="exclamation" class="text-red-600" /> {{ session('error') }}
    </x-account.flash>
@endif