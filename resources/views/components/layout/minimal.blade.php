@props(['title', 'twittercard' => null])

<x-base.page :title="$title" :twittercard="$twittercard">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <div class="bg-white border-b mb-1">
            <x-base.container class="sm:px-3">
                Submenu
            </x-base.container>
        </div>

        @isset($submenu)
            
        @endisset

        <x-ui.status />

        <x-base.container class="sm:px-3 min-h-screen">
            {{ $slot }}
        </x-base.container>

        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
