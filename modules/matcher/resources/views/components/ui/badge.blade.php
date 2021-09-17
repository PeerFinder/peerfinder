@props(['icon' => null])

<div {{ $attributes->merge(['class' => 'inline-flex items-center text-sm py-1 px-2 rounded-md']) }}>@if ($icon)<x-ui.icon name="{{ $icon }}" class="mr-1" /> @endif{{ $slot }}</div>