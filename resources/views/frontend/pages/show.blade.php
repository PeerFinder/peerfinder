<x-layout.minimal>
    <div class="px-4 sm:p-0 mt-5 sm:mt-10 mb-5 sm:mb-10">
        <x-slot name="title">
            {{ $title }}
        </x-slot>
    
        <div class="mt-5 sm:mt-10">
            <h1 class="text-3xl font-semibold">{{ $title }}</h1>
        </div>
    
        <div class="mt-5 bg-white p-4 w-full rounded-md shadow-sm">
            {!! $body !!}
        </div>
    </div>
</x-layout.minimal>