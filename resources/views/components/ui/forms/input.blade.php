<label for="{{ $attributes->get('name') }}">{{ $slot }}</label>

<input {{ $attributes->merge(['placeholder' => $slot, 'class' => 'w-full border border-gray-300 px-4 py-3 rounded-md shadow-sm'. ($errors->first($attributes->get('name')) ? ' bg-red-100 border-red-500': '') ]) }} />