@props(['status'])

@if ($status)
    <x-auth.flash class="bg-emerald-200">
        {{ $status }}
    </x-auth.flash>
@endif

