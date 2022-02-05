@props(['limit' => 0, 'values' => []])

<label for="{{ $attributes->get('name') }}" class="block mb-1 font-medium">{{ $slot }}@if($attributes->get('required'))<span class="text-red-500 ml-1">*</span>@endif @error($attributes->get('name')) <x-ui.icon name="exclamation" class="text-red-500" />@enderror</label>

<tags-input name="{{ $attributes->get('name') }}" limit="{{ $limit }}" :old="{{ json_encode(old($attributes->get('name'), old('_token') ? [] : $values)) }} "></tags-input>