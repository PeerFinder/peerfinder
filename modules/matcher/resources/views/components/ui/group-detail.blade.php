@if ($slot)
<span class="inline-flex items-center"><x-ui.icon name="{{ $icon }}" /><span class="inline-block ml-1">@isset($title){{ $title }}: @endisset{{ $slot }}</span></span>
@endif