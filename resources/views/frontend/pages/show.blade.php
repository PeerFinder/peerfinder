<x-layout.minimal>
    <div class="px-4 sm:p-0 mt-5 sm:mt-10 mb-5 sm:mb-10">
        <x-slot name="title">
            {{ $title }}
        </x-slot>
    
        <div class="mt-5 sm:mt-10">
            <x-ui.h1>{{ $title }}</x-ui.h1>
        </div>
    
        <div class="mt-5 bg-white p-5 sm:p-10 w-full rounded-md shadow-sm">
            {!! $body !!}
        </div>
    </div>
</x-layout.minimal>