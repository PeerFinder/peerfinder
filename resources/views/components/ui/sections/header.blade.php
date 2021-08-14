<header id="header" class="shadow bg-gray-800">
    <x-base.container class="px-3 flex justify-between py-2 items-center flex-row">
        <div class="bg-yellow-200">
            <a href="{{ route('dashboard.index') }}" class="flex items-center">
                <img src="{{ asset('/images/peerfinder_logo.png') }}" srcset="{{ asset('/images/peerfinder_logo@2x.png') }} 2x" class="w-7" />
                <div class="text-2xl ml-2 text-gray-300">{{ config('app.name') }}</div>
            </a>
        </div>

        <div class="flex items-center space-x-6 flex-row">

            <header-menu>
                <template v-slot:trigger>
                    <a class="text-gray-300 hover:text-white" href="#"><x-ui.user.avatar :user="$user" size="40" class="rounded-full inline-block" /><x-ui.icon name="chevron" class="" /></a>
                </template>
                <template v-slot:content>
                    <p class="text-sm px-5 pt-4">{{ __('profile/user.signed_in_as') }}</p>
                    <h2 class="text-xl font-bold px-5 pb-3">{{ $user->name }}</h2>
                    <nav class="mb-4">
                        <x-ui.sections.header.nav-item route="account.profile.edit">{{ __('profile/user.menu_my_account') }}</x-ui.sections.header.nav-item>
                    </nav>
                    <a href="{{ route('logout') }}" class="block m-2 py-2 px-3 rounded-md text-center bg-gray-50 hover:bg-red-300 text-red-700 hover:text-red-800">{{ __('profile/user.menu_logout') }}</a>
                </template>
            </header-menu>

            <div class="relative">
                <a class="text-gray-300 hover:text-white" href="#"><x-ui.user.avatar :user="$user" size="40" class="rounded-full inline-block" /><x-ui.icon name="chevron" class="" /></a>

                <div class="sr-only absolute right-3 top-1/2 w-60 bg-white rounded-md overflow-hidden shadow-md">
                    <div class="after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block">
                        <p class="text-sm px-5 pt-4">{{ __('profile/user.signed_in_as') }}</p>
                        <h2 class="text-xl font-bold px-5 pb-3">{{ $user->name }}</h2>
                        <nav class="mb-4">
                            <x-ui.sections.header.nav-item route="account.profile.edit">{{ __('profile/user.menu_my_account') }}</x-ui.sections.header.nav-item>
                        </nav>
                        <a href="{{ route('logout') }}" class="block m-2 py-2 px-3 rounded-md text-center bg-gray-50 hover:bg-red-300 text-red-700 hover:text-red-800">{{ __('profile/user.menu_logout') }}</a>
                    </div>
                </div>
            </div>

        </div>
    </x-base.container>
</header>