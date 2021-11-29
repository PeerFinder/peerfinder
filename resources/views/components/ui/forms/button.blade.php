@props(['action' => 'execute', 'tag' => 'button', 'vueClick' => null])

@if ($action == 'execute')
<{{ $tag }} @if ($vueClick) @@click.prevent="{{$vueClick}}" @endif {{ $attributes->merge(['class' => 'bg-pf-midblue active:bg-pf-darkblue active:border-black py-1 px-6 text-white inline-block text-center rounded-md shadow border border-pf-darkblue transition-colors']) }}>{{ $slot }}</{{ $tag }}>
@elseif ($action == 'inform')
<{{ $tag }} @if ($vueClick) @@click.prevent="{{$vueClick}}" @endif {{ $attributes->merge(['class' => 'bg-gradient-to-b from-gray-50 to-gray-100 active:from-gray-50 active:to-gray-200 active:border-gray-400 py-1 px-6 text-gray-600 inline-block text-center rounded-md shadow-sm border border-gray-300 transition-colors']) }}>{{ $slot }}</{{ $tag }}>
@elseif ($action == 'destroy')
<{{ $tag }} @if ($vueClick) @@click.prevent="{{$vueClick}}" @endif {{ $attributes->merge(['class' => 'bg-red-200 active:bg-red-300 active:border-red-400 py-1 px-6 text-red-700 inline-block text-center rounded-md shadow-sm border border-red-300 transition-colors']) }}>{{ $slot }}</{{ $tag }}>
@elseif ($action == 'create')
<{{ $tag }} @if ($vueClick) @@click.prevent="{{$vueClick}}" @endif {{ $attributes->merge(['class' => 'bg-green-500 active:bg-green-600 active:border-green-700 py-1 px-6 text-white inline-block text-center rounded-md shadow border border-green-600 transition-colors']) }}>{{ $slot }}</{{ $tag }}>
@elseif ($action == 'attention')
<{{ $tag }} @if ($vueClick) @@click.prevent="{{$vueClick}}" @endif {{ $attributes->merge(['class' => 'bg-pf-darkorange active:bg-pf-midorange active:border-pf-midorange py-1 px-6 text-white inline-block text-center rounded-md shadow border border-pf-darkorange transition-colors']) }}>{{ $slot }}</{{ $tag }}>
@endif