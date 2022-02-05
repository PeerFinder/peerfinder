<nav class="text-center space-x-3 text-sm">
    <x-ui.link href="{{ route('page.show', ['slug' => 'terms-of-service', 'language' => app()->getLocale()]) }}">{{ __('auth.terms_of_service') }}</x-ui.link>
    <x-ui.link href="{{ route('page.show', ['slug' => 'privacy-policy', 'language' => app()->getLocale()]) }}">{{ __('auth.privacy_policy') }}</x-ui.link>
    <x-ui.link href="{{ route('page.show', ['slug' => 'imprint', 'language' => app()->getLocale()]) }}">{{ __('auth.imprint') }}</x-ui.link>
</nav>