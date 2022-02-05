<form {{ $attributes->merge(['method' => 'post', 'class' => 'p-10']) }}>
    {{ $slot }}
</form>