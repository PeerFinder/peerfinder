@props(['title', 'icon'])

@if ($slot && strlen($slot))
<div {{ $attributes->merge(['class' => 'flex mr-4'])}}>
    <x-ui.icon name="{{ $icon }}" class="shrink-0 mt-1 text-gray-600" size="w-4 h-4" />
    <div class="inline-block ml-1">@isset($title){{ $title }}: @endisset{{ $slot }}</div>
</div>
@endif