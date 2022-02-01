<x-layout.fullwidth>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <div class="min-h-screen">
        {!! $body !!}
    </div>
</x-layout.fullwidth>