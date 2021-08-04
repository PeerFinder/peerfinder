<nav class="text-center space-x-3 text-sm">
    <x-ui.link href="{{ route('info', ['slug' => 'terms-of-service']) }}">{{ __('auth.terms_of_service') }}</x-ui.link>
    <x-ui.link href="{{ route('info', ['slug' => 'privacy-policy']) }}">{{ __('auth.privacy_policy') }}</x-ui.link>
    <x-ui.link href="{{ route('info', ['slug' => 'imprint']) }}">{{ __('auth.imprint') }}</x-ui.link>
</nav>