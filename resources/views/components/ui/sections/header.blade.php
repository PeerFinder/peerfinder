<header id="header" class="shadow bg-[#1F303A]">
    <div class="max-w-6xl mx-auto px-3 flex py-2.5 items-center">
        {{-- App logo --}}
        <div class="mr-5 sm:mr-10">
            <a href="{{ Auth::check() ? route('dashboard.index') : route('index') }}" class="flex items-center">
                <img src="{{ Urler::versioned_asset('/images/peerfinder_logo.png') }}" srcset="{{ Urler::versioned_asset('/images/peerfinder_logo@2x.png') }} 2x" class="w-7" alt="{{ config('app.name') }}" />
                <div class="text-2xl ml-2 text-gray-200 hidden sm:block">{{ config('app.name') }}</div>
            </a>
        </div>

        <div id="header-nav" class="flex-1 flex items-center justify-between">
            @auth
            {{-- Generic menu --}}
            <div class="flex-1">
                <collapsable-header-menu break-point="900" dropdown-class="w-64">
                    <template v-slot:trigger>
                        <div class="text-pf-lightblue hover:text-white bg-pf-darkblue inline-flex p-2 px-3 rounded-md space-x-2 items-center border border-pf-darkblue">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>@lang('profile/user.menu')</span>
                        </div>
                    </template>
                    <template v-slot:content>
                        <div class="form-wrapper">
                            <x-ui.forms.form method="get" action="{{ route('matcher.index') }}" autocomplete="off">
                                <input class="search-field" name="search" value="{{ request()->get('search') }}" placeholder="@lang('profile/user.search_placeholder')" />
                                <button class="browse-button" href="#">@lang('profile/user.menu_browse_groups')</button>
                                <a class="new-button" href="{{ route('matcher.create') }}"><x-ui.icon name="plus-circle" viewBox="0 2 20 20" /> @lang('profile/user.menu_new_group')</a>
                            </x-ui.forms.form>
                        </div>

                        <ul class="links">
                            <li><a href="https://peerfinder.app/blog/" target="_blank">@lang('profile/user.menu_link_blog')</a></li>
                            <li><a href="https://peerfinder.app/blog/category/hilfe/" target="_blank">@lang('profile/user.menu_link_help')</a></li>
                        </ul>
                    </template>
                </collapsable-header-menu>
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
                <collapsable-header-menu dropdown-class="w-60">
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
                </collapsable-header-menu>
            </div>
            @endauth
        </div>
    </div>
</header>