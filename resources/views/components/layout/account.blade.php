<x-base.page :title="$title">

    <body class="bg-gray-100">

        <div class="w-full max-w-5xl mx-auto mb-10">
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
                        <h2 class="text-2xl px-10 pt-10">{{ $title }}</h2>

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
