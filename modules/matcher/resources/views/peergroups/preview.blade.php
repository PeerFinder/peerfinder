<x-layout.minimal :title="$pg->title">
    <x-slot name="twittercard">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@@peerfinderapp" />
        <meta property="og:url" content="{{ route('matcher.preview', ['groupname' => $pg->groupname]) }}" />
        <meta property="og:title" content="{{ $pg->title }}" />
        <meta property="og:description" content="{{ $pg->description }}" />
    </x-slot>

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10 sm:grid sm:grid-cols-10 gap-7">
        <div class="sm:col-span-6 lg:col-span-7">
            <div class="px-4 sm:p-0">
                <x-ui.h1>
                    {{ $pg->title }}
                </x-ui.h1>
                @if($pg->private)<x-matcher::ui.badge icon="eye-off" class="bg-purple-400 mt-2">{{ __('matcher::peergroup.badge_private') }}</x-matcher::ui.badge>@endif
                @if(!$pg->open)<x-matcher::ui.badge icon="lock-closed" class="bg-yellow-400 mt-2">{{ __('matcher::peergroup.badge_closed') }}</x-matcher::ui.badge>@endif

                <div class="mt-7 space-x-5">
                    <x-matcher::ui.user role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
                </div>
            </div>

            <div class="mt-5 space-y-5 sm:space-y-7">
                <x-ui.card title="{{ __('matcher::peergroup.group_description') }}">
                    <div class="p-4 pb-4">
                        <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
                        @if ($pg->virtual)
                        <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
                        @else
                        <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
                        @endif
                        <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $pg->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
                    </div>

                    <div class="px-4 pb-4">
                        {{ $pg->description }}
                    </div>
                </x-ui.card>

                <div>
                    <x-ui.card class="p-4 text-center border-2 border-pf-darkorange space-y-2">
                        <div class="mb-2">{{ __('matcher::peergroup.please_login_to_see_details') }}</div>
                        <x-ui.sections.header.button class="bg-pf-darkblue inline-flex items-center hover:bg-pf-midblue" href="{{ route('login') }}">{{ __('profile/user.button_login') }}</x-ui.sections.header.button>
                        <x-ui.sections.header.button class="bg-pf-darkorange inline-flex items-center hover:bg-pf-midorange" href="{{ route('register') }}">{{ __('profile/user.button_register') }}</x-ui.sections.header.button>
                    </x-ui.card>
                </div>
            </div>
        </div>

        <div class="sm:col-span-4 lg:col-span-3 space-y-5 sm:space-y-7 mt-5 sm:mt-0">
            <x-matcher::peergroup.members-list :pg="$pg" anonymous="true" />
        </div>
    </div>
</x-layout.minimal>