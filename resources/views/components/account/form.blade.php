<form {{ $attributes->merge(['method' => 'post']) }}>
    {{ $slot }}
</form>