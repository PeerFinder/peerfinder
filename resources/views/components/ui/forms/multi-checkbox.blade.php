@props(['default' => null, 'selection' => null, 'key' => 'id', 'name' => null, 'required' => false])

<h2 class="block mb-1 font-medium">{{ $slot }}@if($required)<span class="text-red-500 ml-1">*</span>@endif @error($name) <x-ui.icon name="exclamation" class="text-red-500" />@enderror</h2>

<div class="border w-full px-4 py-2 rounded-md shadow-sm {{ ($errors->first($name) ? ' bg-red-100 border-red-500': ' bg-gray-50 border-gray-300') }}">
    @foreach ($selection as $item)
    @php
    $checked = (old('_token') !== null ) ? old($name) && is_array(old($name)) && in_array($item->$key, old($name)) : $default->contains($item);
    @endphp
    <div class="inline-flex items-center mt-1 mr-3">
        <input value="{{ $item->$key }}" {{ $attributes->merge(['type' => 'checkbox', 'name' => $name . '[]', 'class' => 'border-2 focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent border-gray-300 rounded-sm']) }} id="{{ $name }}_{{ $item->$key }}" @if ($checked) checked @endif />
        <label class="text-sm ml-1" for="{{ $name }}_{{ $item->$key }}">{{ $item->title }}</label>
    </div>
    @endforeach
</div>