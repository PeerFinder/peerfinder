@props(['status'])

@if ($status)
    <x-auth.flash class="bg-green-200">
        {{ $status }}
    </x-auth.flash>
@endif

