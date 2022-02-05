@props(['icon' => null])

@if ($icon)
<a {{ $attributes->merge(['class' => 'flex items-center text-pf-midblue hover:text-pf-lightblue']) }}><x-ui.icon :name="$icon" /> <span class="ml-1 inline-block underline">{{ $slot }}</span></a>
@else
<a {{ $attributes->merge(['class' => 'underline text-pf-midblue hover:text-pf-lightblue']) }}>{{ $slot }}</a>
@endif
