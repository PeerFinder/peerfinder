<x-layout.minimal>
    <div class="px-4 sm:p-0">
        <x-slot name="title">
            {{ $title }}
        </x-slot>
    
        <div class="mt-5 sm:mt-10">
            <h1 class="text-3xl font-semibold">{{ $title }}</h1>
        </div>
    
        <div class="prose mt-5">
            {!! $body !!}
        </div>
    </div>
</x-layout.minimal>