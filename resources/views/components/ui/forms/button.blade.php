@props(['action' => 'execute'])

@if ($action == 'execute')
<button {{ $attributes->merge(['class' => 'bg-pf-midblue active:bg-pf-darkblue active:border-black py-1 px-6 text-white rounded-md shadow border border-pf-darkblue transition-colors']) }}>{{ $slot }}</button>
@elseif ($action == 'inform')
<button {{ $attributes->merge(['class' => 'bg-gray-100 active:bg-gray-200 active:border-gray-400 py-1 px-6 text-gray-600 rounded-md shadow-sm border border-gray-300 transition-colors']) }}>{{ $slot }}</button>
@elseif ($action == 'destroy')
<button {{ $attributes->merge(['class' => 'bg-red-200 active:bg-red-300 active:border-red-400 py-1 px-6 text-red-700 rounded-md shadow-sm border border-red-300 transition-colors']) }}>{{ $slot }}</button>
@elseif ($action == 'create')
<button {{ $attributes->merge(['class' => 'bg-green-500 active:bg-green-600 active:border-green-700 py-1 px-6 text-white rounded-md shadow border border-green-600 transition-colors']) }}>{{ $slot }}</button>
@endif