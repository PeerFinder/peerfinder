@if (session('success'))
    <x-account.flash class="bg-green-200 border-green-500">
        {{ session('success') }}
    </x-account.flash>
@endif

@if (session('error'))
    <x-account.flash class="bg-red-300 border-red-500">
        {{ session('error') }}
    </x-account.flash>
@endif