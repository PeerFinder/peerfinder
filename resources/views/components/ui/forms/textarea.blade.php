@props(['value' => ''])

@if ($slot != "")
<label for="{{ $attributes->get('name') }}" class="block mb-1 font-medium">{{ $slot }}@if($attributes->get('required'))<span class="text-red-500 ml-1">*</span>@endif @error($attributes->get('name')) <x-ui.icon name="exclamation" class="text-red-500" />@enderror</label>
@endif

<textarea {{ $attributes->merge(['class' => 'w-full border px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-pf-midblue focus:border-transparent focus:bg-white'. ($errors && $errors->first($attributes->get('name')) ? ' bg-red-100 border-red-500': ' bg-gray-50 border-gray-300') ]) }}>{{ $value }}</textarea>