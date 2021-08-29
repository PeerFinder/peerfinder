@if ($slot && strlen($slot))
<span class="inline-flex items-center mr-4"><x-ui.icon name="{{ $icon }}" /><span class="inline-block ml-1">@isset($title){{ $title }}: @endisset<strong class="bg-gray-50 inline-block px-2 rounded-md">{{ $slot }}</strong></span></span>
@endif