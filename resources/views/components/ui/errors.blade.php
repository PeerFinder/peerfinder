@if ($errors->any())
<x-ui.flash {{ $attributes->merge([ 'class' => 'bg-red-300 border-red-500 rounded-lg shadow border']) }}>
    <ul>
        @foreach ($errors->all() as $error)
        <li>
            <x-ui.icon name="exclamation" class="text-red-600" /> {{ $error }}
        </li>
        @endforeach
    </ul>
</x-ui.flash>
@endif