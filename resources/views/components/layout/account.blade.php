<x-base.page :title="$title">
    <x-base.app>
        <x-ui.sections.header :user="$currentUser" />

        <x-ui.status />

        <x-base.container class="mb-10 sm:px-3 pt-10 min-h-screen">
            <div class="bg-white shadow-sm after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block sm:rounded-md overflow-hidden">

                <div class="grid grid-cols-10 sm:grid-cols-5">
                    <div class="col-span-1 bg-gray-50 border-r border-gray-200">
                        <h1 class="hidden sm:block px-4 py-2 border-b border-gray-200 bg-white">{{ __('account/account.title') }}</h1>

                        <nav class="sm:p-2">
                            <x-account.nav-item route="account.profile.edit" icon="user-circle">{{ __('account/account.menu_profile') }}</x-account.nav-item>
                            <x-account.nav-item route="account.avatar.edit" icon="photograph">{{ __('account/account.menu_avatar') }}</x-account.nav-item>
                            <x-account.nav-item route="account.email.edit" icon="mail">{{ __('account/account.menu_email') }}</x-account.nav-item>
                            <x-account.nav-item route="account.password.edit" icon="key">{{ __('account/account.menu_password') }}</x-account.nav-item>
                            <x-account.nav-item route="account.account.edit" icon="shield-exclamation">{{ __('account/account.menu_account') }}</x-account.nav-item>
                        </nav>
                    </div>

                    <div class="col-span-9 sm:col-span-4">
                        <div class="px-10 pt-10 sm:flex justify-between items-center space-y-5 sm:space-y-0">
                            <h2 class="font-semibold text-2xl">{{ $title }}</h2>
                            @if (Route::currentRouteNamed(['account.profile.edit', 'account.avatar.edit']))
                            <div>
                                <x-ui.forms.button tag="a" href="{{ Urler::userProfileUrl($currentUser) }}" target="_blank" action="inform">{{ __('account/account.button_show_profile') }}</x-ui.forms.button>
                            </div>
                            @endif
                        </div>

                        <x-ui.errors :errors="$errors" class="mx-10 mt-10 p-3" />

                        <div class="p-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </x-base.container>

        <x-ui.sections.footer />
    </x-base.app>
</x-base.page>
