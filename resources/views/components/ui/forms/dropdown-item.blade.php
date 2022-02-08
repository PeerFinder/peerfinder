@props(['href' => '', 'disabled' => false])

@unless ($disabled)
@if ($href)
<a class="px-3 py-2 block bg-white hover:bg-gray-50" href="{{ $href }}">{{ $slot }}</a>
@else
<button class="px-3 py-2 bg-white hover:bg-gray-50 text-left w-full">{{ $slot }}</button>
@endif
@else
<span class="px-3 py-2 block bg-white text-gray-400 text-left w-full cursor-not-allowed">{{ $slot }}</span>
@endunless
