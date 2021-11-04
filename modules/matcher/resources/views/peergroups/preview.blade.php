<x-layout.minimal :title="$pg->title">
    <x-slot name="twittercard">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@@peerfinderapp" />
        <meta property="og:url" content="{{ route('matcher.preview', ['groupname' => $pg->groupname]) }}" />
        <meta property="og:title" content="{{ $pg->title }}" />
        <meta property="og:description" content="{{ $pg->description }}" />
        <meta property="og:image" content="{{ Matcher::getGroupImageLink($pg) }}" />
    </x-slot>

    <div class="sm:mt-10 md:grid md:grid-cols-10 gap-7 mb-5 sm:mb-7">
        <div class="sm:col-span-6 lg:col-span-7 relative">
            <a href="{{ $pg->getUrl() }}">
                <img src="{{ Matcher::getGroupImageLink($pg) }}" alt="{{ $pg->image_alt }}" class="sm:rounded-md" />
            </a>
        </div>
        <div class="sm:col-span-4 lg:col-span-3 space-y-5 sm:space-y-7 mt-5 md:mt-0 px-4 sm:px-0">
            <x-ui.h1>
                {{ $pg->title }}
            </x-ui.h1>

            @if($pg->private)<x-matcher::ui.badge icon="eye-off" class="bg-purple-400 mt-2">{{ __('matcher::peergroup.badge_private') }}</x-matcher::ui.badge>@endif
            @if(!$pg->open)<x-matcher::ui.badge icon="lock-closed" class="bg-yellow-400 mt-2">{{ __('matcher::peergroup.badge_closed') }}</x-matcher::ui.badge>@endif

            <div class="mt-7 space-x-5">
                <x-matcher::ui.user role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
            </div>
        </div>
    </div>

    <div class="sm:mt-10 mb-5 sm:mb-10 sm:grid sm:grid-cols-10 gap-7">
        <div class="sm:col-span-6 lg:col-span-7">
            <div class="space-y-5 sm:space-y-7">
                <div>
                    <x-ui.card class="p-4 text-center border-2 border-pf-darkorange space-y-2">
                        <div class="mb-2">{{ __('matcher::peergroup.please_login_to_see_details') }}</div>
                        <x-ui.sections.header.button class="bg-pf-darkblue inline-flex items-center hover:bg-pf-midblue" href="{{ route('login') }}">{{ __('profile/user.button_login') }}</x-ui.sections.header.button>
                        <x-ui.sections.header.button class="bg-pf-darkorange inline-flex items-center hover:bg-pf-midorange" href="{{ route('register') }}">{{ __('profile/user.button_register') }}</x-ui.sections.header.button>
                    </x-ui.card>
                </div>

                @include('matcher::partials.group-description')
            </div>
        </div>

        <div class="sm:col-span-4 lg:col-span-3 space-y-5 sm:space-y-7 mt-5 sm:mt-0">
            @include('matcher::partials.members-list', ['anonymous' =>  true])
        </div>
    </div>
</x-layout.minimal>