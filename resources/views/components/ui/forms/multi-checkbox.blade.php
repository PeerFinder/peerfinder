@props(['default' => null, 'selection' => null, 'key' => 'id'])

<h2 class="block mb-1 font-medium">{{ $slot }}</h2>

<div class="border bg-gray-50 w-full px-4 py-2 rounded-md shadow-sm">
@foreach ($selection as $item)
    <div class="inline-flex items-center mt-1 mr-3">
        <input {{ $attributes->merge(['type' => 'checkbox', 'class' => 'border-2 focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent border-gray-300 rounded-sm']) }} id="{{ $attributes->get('name') }}_{{ $item->$key }}" name="{{ $attributes->get('name') }}[{{ $item->$key }}]" />
        <label class="text-sm ml-1" for="{{ $attributes->get('name') }}_{{ $item->$key }}">{{ $item->title }}</label>
    </div>
@endforeach
</div>

{{-- Hidden input with value of 0 for using old() --}}
<input name="{{ $attributes->get('name') }}" type="hidden" value="0" />


{{-- 
    <input value="1" {{ $attributes->merge(['type' => 'checkbox', 'class' => 'border-2 focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent border-gray-300 rounded-sm shadow-sm']) }} {{ old($attributes->get('name'), $default) ? 'checked' : '' }} />
    --}}
