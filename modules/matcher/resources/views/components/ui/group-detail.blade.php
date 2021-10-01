@props(['title', 'icon'])

@if ($slot && strlen($slot))
<span {{ $attributes->merge(['class' => 'inline-flex items-center mr-4'])}}><x-ui.icon name="{{ $icon }}" /><span class="inline-block ml-1">@isset($title){{ $title }}: @endisset<strong class="">{{ $slot }}</strong></span></span>
@endif