@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white shadow-sm sm:rounded-md overflow-hidden']) }}>
    @if ($title)
        @if ($subtitle)
        <h2 class="text-lg font-semibold px-4 pb-0 py-2 text-gray-600">{{ $title }}</h2>
        <h3 class="text-sm px-4 pb-2 text-gray-600 border-b">{{ $subtitle }}</h3>
        @else
        <h2 class="text-lg font-semibold px-4 py-2 border-b text-gray-600">{{ $title }}</h2>
        @endif
    @endif

    {{ $slot }}
</div>