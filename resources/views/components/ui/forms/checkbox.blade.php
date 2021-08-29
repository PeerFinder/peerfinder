@props(['default' => null])
{{-- Hidden input with value of 0 for using old() --}}
<input name="{{ $attributes->get('name') }}" type="hidden" value="0" />
<input value="1" {{ $attributes->merge(['type' => 'checkbox', 'class' => 'border-2 focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent border-gray-300 rounded-sm shadow-sm']) }} {{ old($attributes->get('name'), $default) ? 'checked' : '' }} />
<label class="text-sm ml-1" for="{{ $attributes->get('name') }}">{{ $slot }}</label>