@props(['title' => null, 'subtitle' => null, 'edit' => null, 'can' => false])

<div {{ $attributes->merge(['class' => 'bg-white shadow-sm sm:rounded-md overflow-hidden']) }}>
    @if ($title)
        <div class="border-b flex items-center px-4">
            <div class="flex-1">
                @if ($subtitle)
                <h2 class="font-semibold pb-0 py-2 text-gray-600">{{ $title }}</h2>
                <h3 class="text-sm pb-2 text-gray-400">{{ $subtitle }}</h3>
                @else
                <h2 class="font-semibold py-2 text-gray-600">{{ $title }}</h2>
                @endif
            </div>
            @if ($edit && $can)
            <div>
                <a href="{{ $edit }}" class="text-gray-300 hover:text-gray-500"><x-ui.icon name="pencil-alt" /></a>
            </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>