<header id="header" class="shadow bg-[#1F303A]">
    <div class="max-w-6xl mx-auto px-3 flex py-2.5 items-center">
        {{-- App logo --}}
        <div class="mr-10">
            <a href="{{ Auth::check() ? route('dashboard.index') : route('index') }}" class="flex items-center">
                <img src="{{ Urler::versioned_asset('/images/peerfinder_logo.png') }}" srcset="{{ Urler::versioned_asset('/images/peerfinder_logo@2x.png') }} 2x" class="w-7" alt="{{ config('app.name') }}" />
                <div class="text-2xl ml-2 text-gray-200 hidden sm:block">{{ config('app.name') }}</div>
            </a>
        </div>

        <div class="flex-1 flex items-center justify-between">
            @auth
            {{-- Generic menu --}}
            <div class="flex-1 flex justify-between">
                {{-- <header-menu class="md:hidden">
                    <template v-slot:trigger>
                        <div class="text-gray-200 hover:text-white bg-pf-darkblue inline-flex p-2 px-3 rounded-md space-x-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>@lang('profile/user.menu')</span>
                        </div>
                    </template>
                    <template v-slot:content>
                        <nav class="my-4">
                            <x-ui.sections.header.nav-item route="matcher.index">@lang('profile/user.menu_find_peers')</x-ui.sections.header.nav-item>
                            <x-ui.sections.header.nav-item route="matcher.create">@lang('profile/user.menu_new_group')</x-ui.sections.header.nav-item>
                        </nav>
                    </template>
                </header-menu> --}}

                <div class="flex flex-1 items-center mr-10">
                    <x-ui.forms.form method="get" action="{{ route('matcher.index') }}" class="flex-1 flex" autocomplete="off">
                        <input class="px-3 py-2 rounded-r-none rounded-md bg-pf-darkblue border border-pf-midblue w-full min-w-60 shadow-inner placeholder-gray-300 text-white focus:outline-none focus:border-pf-lightblue" name="search" value="{{ request()->get('search') }}" placeholder="@lang('profile/user.search_placeholder')" />
                        <button class="py-2 px-4 border border-pf-midblue border-l-0 active:bg-pf-darkblue rounded-md rounded-l-none text-pf-lightblue hover:text-white" href="#">@lang('profile/user.menu_browse_groups')</button>
                        <a class="block ml-1 py-2 px-4 border whitespace-nowrap border-green-700 active:bg-green-600 rounded-md text-green-400 hover:text-white" href="#"><x-ui.icon name="plus-circle" viewBox="0 2 20 20" /> @lang('profile/user.menu_new_group')</a>
                    </x-ui.forms.form>
                </div>

                <ul class="flex space-x-4 items-center justify-center">
                    <li><a class="text-pf-lightblue hover:text-white font-semibold" href="{{ route('matcher.create') }}">Feedback</a></li>
                    <li><a class="text-pf-lightblue hover:text-white font-semibold" href="{{ route('matcher.create') }}">Help</a></li>
                    <li><a class="text-pf-lightblue hover:text-white font-semibold" href="{{ route('matcher.create') }}">Blog</a></li>
                </ul>
            </div>
            @endauth
        </div>

        <div class="flex justify-end ml-10">
            @guest
            {{-- Login and register menu --}}
            <div class="flex-1 flex justify-end items-center space-x-2 py-1">
                <x-ui.sections.header.button class="bg-pf-darkblue inline-flex items-center hover:bg-pf-midblue" href="{{ route('login') }}"><x-ui.icon name="user-circle" class="mr-1" />@lang('profile/user.button_login')</x-ui.sections.header.button>
                @if (Route::has('register'))
                <x-ui.sections.header.button class="bg-pf-darkorange hover:bg-pf-midorange active:bg-pf-darkorange" href="{{ route('register') }}">@lang('profile/user.button_register')</x-ui.sections.header.button>
                @endif
            </div>
            @endguest
            
            @auth
            <div class="flex items-center">
                {{-- Notification and Messages menu --}}
                <div class="mr-5 flex space-x-4">
                    <div class="relative">
                        <a href="{{ route('notifications.index') }}"><x-ui.icon name="bell" class="w-7 h-7 text-pf-lightblue hover:text-white" /></a>
                        @if ($user->unreadNotifications->isNotEmpty())
                        <div class="absolute rounded-full -right-0.5 top-0 w-3 h-3 bg-red-500 shadow-sm"></div>
                        @endif
                    </div>
                    <div class="relative">
                        <a href="{{ Talk::dynamicConversationsUrl($user) }}"><x-ui.icon name="mail" class="w-7 h-7 text-pf-lightblue hover:text-white" /></a>
                        @if (Talk::userHasUnreadConversations($user))
                        <div class="absolute rounded-full -right-0.5 top-0 w-3 h-3 bg-red-500 shadow-sm"></div>
                        @endif
                    </div>
                </div>
    
                {{-- User menu --}}
                <header-menu>
                    <template v-slot:trigger>
                        <span class="text-pf-lightblue hover:text-white group whitespace-nowrap"><x-ui.user.avatar :user="$user" size="40" class="rounded-full inline-block" /><x-ui.icon name="chevron" /></span>
                    </template>
                    <template v-slot:content>
                        <p class="text-sm px-5 pt-4">@lang('profile/user.signed_in_as')</p>
                        <h2 class="text-xl font-semibold px-5 pb-3">{{ $user->name }}</h2>
                        <nav class="mb-4">
                            <x-ui.sections.header.nav-item route="dashboard.index">@lang('profile/user.menu_my_dashboard')</x-ui.sections.header.nav-item>
                            <x-ui.sections.header.nav-item route="profile.user.index">@lang('profile/user.menu_my_profile')</x-ui.sections.header.nav-item>
                            <x-ui.sections.header.nav-item route="account.profile.edit">@lang('profile/user.menu_my_account')</x-ui.sections.header.nav-item>
                        </nav>
                        <a href="{{ route('logout') }}" class="block m-2 py-2 px-3 rounded-md text-center bg-gray-50 hover:bg-red-300 text-red-700 hover:text-red-800">@lang('profile/user.menu_logout')</a>
                    </template>
                </header-menu>
            </div>
            @endauth
        </div>
    </div>
</header>

{{-- 
<div class="bg-white">
    <ul class="flex justify-center space-x-5 shadow">
        <li><a class="py-4 block text-pf-midblue hover:text-pf-lightblue font-semibold" href="{{ route('matcher.index') }}">@lang('profile/user.menu_browse_groups')</a></li>
        <li><a class="py-4 block text-pf-midblue hover:text-pf-lightblue font-semibold" href="{{ route('matcher.create') }}">@lang('profile/user.menu_new_group')</a></li>
    </ul>
</div> --}}