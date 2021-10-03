<div class="bg-gray-50 pt-8 pb-3">
    <x-base.container class="mb-5 px-3">
        <footer class="text-center">
            <div class="text-sm">
                Presented by {{ config('app.name') }}. Made in Hannover with ❤️ by <a href="https://twitter.com/leonidlezner">Leonid Lezner</a>.
            </div>
    
            <nav class="mt-2 text-center space-x-3 text-sm">
                <x-ui.link href="{{ route('page.show', ['slug' => 'terms-of-service', 'language' => app()->getLocale()]) }}">{{ __('auth.terms_of_service') }}</x-ui.link>
                <x-ui.link href="{{ route('page.show', ['slug' => 'privacy-policy', 'language' => app()->getLocale()]) }}">{{ __('auth.privacy_policy') }}</x-ui.link>
                <x-ui.link href="{{ route('page.show', ['slug' => 'imprint', 'language' => app()->getLocale()]) }}">{{ __('auth.imprint') }}</x-ui.link>
            </nav>
        </footer>
    </x-base.container>
</div>