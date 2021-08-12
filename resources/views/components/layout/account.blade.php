<x-base.page :title="$title">
    <body class="bg-gray-100">
        <x-ui.sections.header :user="$currentUser" />

        <div class="w-full max-w-5xl mx-auto mb-10 px-3">
            <h1 class="mt-10 mb-5 text-3xl">{{ __('account/account.title') }}</h1>

            <div class="bg-white shadow-md after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block sm:rounded-md overflow-hidden">

                <div class="grid grid-cols-10 sm:grid-cols-5">
                    <div class="col-span-1 bg-gray-50">
                        <nav>
                            <x-account.nav-item route="account.profile.edit" icon="user-circle">{{ __('account/account.menu_profile') }}</x-account.nav-item>
                            <x-account.nav-item route="account.avatar.edit" icon="photograph">{{ __('account/account.menu_avatar') }}</x-account.nav-item>
                            <x-account.nav-item route="account.email.edit" icon="mail">{{ __('account/account.menu_email') }}</x-account.nav-item>
                            <x-account.nav-item route="account.password.edit" icon="key">{{ __('account/account.menu_password') }}</x-account.nav-item>
                            <x-account.nav-item route="account.account.edit" icon="shield-exclamation">{{ __('account/account.menu_account') }}</x-account.nav-item>
                        </nav>
                    </div>

                    <div class="col-span-9 sm:col-span-4">
                        <div class="px-10 pt-10 sm:flex justify-between items-center space-y-5 sm:space-y-0">
                            <h2 class="text-2xl">{{ $title }}</h2>
                            @if (Route::currentRouteNamed(['account.profile.edit', 'account.avatar.edit']))
                            <div>
                                <x-ui.forms.button tag="a" href="{{ Urler::userProfileUrl($currentUser) }}" target="_blank" action="inform">{{ __('account/account.button_show_profile') }}</x-ui.forms.button>
                            </div>
                            @endif
                        </div>

                        <x-account.status />

                        @if ($errors->any())
                            <x-account.flash class="bg-red-300 border-red-500">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li><x-ui.icon name="exclamation" class="text-red-600" /> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-account.flash>
                        @endif

                        <div class="p-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-base.page>
