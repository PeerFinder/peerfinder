<x-layout.minimal :title="$title">
    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <x-ui.h1>{{ $title }}</x-ui.h1>
        
        {{ $slot }}
    </div>
</x-layout.minimal>