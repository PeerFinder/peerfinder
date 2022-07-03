@props(['title', 'twittercard' => null])

<x-base.page :title="$title" :twittercard="$twittercard">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <x-ui.feedback-button />

        @auth
        <div class="bg-white border-b mb-1">
            <x-base.container class="sm:px-3">
                <div class="flex justify-center align-middle">
                    <x-ui.navigation.submenu-item route="dashboard.index">@lang('submenu.my_dashboard')@if ($dashboardCounter) <span class="ml-1 inline-block px-2 text-white bg-pf-darkorange rounded-md">{{ $dashboardCounter }}</span>@endif</x-ui.navigation.submenu-item>
                    <x-ui.navigation.submenu-item route="matcher.index" :append="['search' => request()->get('search')]">@lang('submenu.groups')</x-ui.navigation.submenu-item>
                    <x-ui.navigation.submenu-item route="group_requests.index" :append="['search' => request()->get('search')]">@lang('submenu.group_requests')</x-ui.navigation.submenu-item>
                    <x-ui.navigation.submenu-item route="profile.user.search" :append="['search' => request()->get('search')]">@lang('submenu.peers')</x-ui.navigation.submenu-item>
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
