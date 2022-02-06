@props(['title', 'twittercard' => null])

<x-base.page :title="$title" :twittercard="$twittercard">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        @auth
        <div class="bg-white border-b mb-1">
            <x-base.container class="sm:px-3">
                <div class="flex justify-center align-middle">
                    <x-ui.navigation.submenu-item route="dashboard.index">@lang('submenu.my_dashboard')</x-ui.navigation.submenu-item>
                    <x-ui.navigation.submenu-item route="matcher.index">@lang('submenu.groups')</x-ui.navigation.submenu-item>
                </div>
            </x-base.container>
        </div>
        @endauth

        <x-ui.status />

        <x-base.container class="sm:px-3 min-h-screen">
            {{ $slot }}
        </x-base.container>

        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
