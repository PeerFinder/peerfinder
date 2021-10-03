<header id="header" class="shadow bg-[#1F303A]">
    <x-base.container class="px-3 flex py-2 items-center">
        <div>
            <a href="{{ Auth::check() ? route('dashboard.index') : route('index') }}" class="flex items-center">
                <img src="{{ Urler::versioned_asset('/images/peerfinder_logo.png') }}" srcset="{{ Urler::versioned_asset('/images/peerfinder_logo@2x.png') }} 2x" class="w-7" alt="{{ config('app.name') }}" />
                <div class="text-2xl ml-2 text-gray-200 hidden sm:block">{{ config('app.name') }}</div>
            </a>
        </div>

        <div class="flex-1 flex items-center justify-between">
            @guest
            <div class="flex-1 flex justify-end items-center space-x-2">
                <x-ui.sections.header.button class="bg-pf-darkblue inline-flex items-center hover:bg-pf-lightblue active:bg-pf-midblue" href="{{ route('login') }}"><x-ui.icon name="user-circle" class="mr-1" />{{ __('profile/user.button_login') }}</x-ui.sections.header.button>
                @if (Route::has('register'))
                <x-ui.sections.header.button class="bg-pf-darkorange hover:bg-pf-midorange active:bg-pf-darkorange" href="{{ route('register') }}">{{ __('profile/user.button_register') }}</x-ui.sections.header.button>
                @endif
            </div>
            @endguest
            
            @auth
            <div class="flex-1 flex justify-center">
                <x-ui.sections.header.button class="bg-pf-darkblue hover:bg-pf-lightblue active:bg-pf-midblue mr-2" href="{{ route('matcher.index') }}">{{ __('profile/user.menu_find_peers') }}</x-ui.sections.header.button>
            </div>

            <div class="flex items-center">
                <div class="mr-5">
                    <div class="relative">
                        <a href="{{ Talk::dynamicConversationsUrl($user) }}"><x-ui.icon name="mail" class="w-7 h-7 text-gray-400 hover:text-white" /></a>
                        @if (Talk::userHasUnreadConversations($user))
                        <div class="absolute rounded-full -right-0.5 top-0 w-3 h-3 bg-pf-darkorange"></div>
                        @endif
                    </div>
                </div>
    
                <header-menu>
                    <template v-slot:trigger>
                        <span class="text-gray-200 hover:text-white"><x-ui.user.avatar :user="$user" size="40" class="rounded-full inline-block" /><x-ui.icon name="chevron" class="" /></span>
                    </template>
                    <template v-slot:content>
                        <p class="text-sm px-5 pt-4">{{ __('profile/user.signed_in_as') }}</p>
                        <h2 class="text-xl font-semibold px-5 pb-3">{{ $user->name }}</h2>
                        <nav class="mb-4">
                            <x-ui.sections.header.nav-item route="dashboard.index">{{ __('profile/user.menu_my_dashboard') }}</x-ui.sections.header.nav-item>
                            <x-ui.sections.header.nav-item route="profile.user.index">{{ __('profile/user.menu_my_profile') }}</x-ui.sections.header.nav-item>
                            <x-ui.sections.header.nav-item route="account.profile.edit">{{ __('profile/user.menu_my_account') }}</x-ui.sections.header.nav-item>
                        </nav>
                        <a href="{{ route('logout') }}" class="block m-2 py-2 px-3 rounded-md text-center bg-gray-50 hover:bg-red-300 text-red-700 hover:text-red-800">{{ __('profile/user.menu_logout') }}</a>
                    </template>
                </header-menu>
            </div>
            @endauth
        </div>
    </x-base.container>
</header>