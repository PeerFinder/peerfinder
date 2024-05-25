<div class="bg-white mt-8 pb-3 before:bg-gradient-to-r before:from-gray-200 before:to-gray-400 before:h-0.5 before:block">
    <x-base.container class="mb-5 pt-8 px-3">
        <footer class="text-center">
            <div class="text-sm inline-block px-5 py-2 rounded-full bg-gray-50">
                Presented by {{ config('app.name') }}. Handcrafted in Hannover with ❤️ by <a href="https://norden.social/@leonid">Leonid Lezner</a>
            </div>
            
            <nav class="mt-2 text-center space-x-3 text-sm">
                <x-ui.link href="{{ route('page.show', ['slug' => 'terms-of-service', 'language' => app()->getLocale()]) }}">{{ __('auth.terms_of_service') }}</x-ui.link>
                <x-ui.link href="{{ route('page.show', ['slug' => 'privacy-policy', 'language' => app()->getLocale()]) }}">{{ __('auth.privacy_policy') }}</x-ui.link>
                <x-ui.link href="{{ route('page.show', ['slug' => 'imprint', 'language' => app()->getLocale()]) }}">{{ __('auth.imprint') }}</x-ui.link>
            </nav>
        </footer>
    </x-base.container>
</div>