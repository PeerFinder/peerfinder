<x-layout.minimal :title="$user->name">
    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <x-ui.card>
            <div class="sm:flex">
                <div class="visual sm:w-1/4">
                    <div class="pt-10 sm:p-10 flex flex-col items-center">
                        <div class="image">
                            <x-ui.user.avatar :user="$user" class="rounded-full text-gray-400" size="200" />
                        </div>
                    </div>
                </div>

                <div class="information sm:w-3/4">
                    <div class="p-10 sm:pl-0 sm:pr-10 sm:py-12">
                        <x-ui.h1>{{ $user->name }}</x-ui.h1>

                        @if ($user->slogan)
                        <p class="text-gray-400 mb-4">{{ $user->slogan }}</p>
                        @endif

                        @if ($user->tags->count())
                        <div class="flex flex-wrap">
                            @foreach ($user->tags as $tag)
                                <div class="bg-gray-50 text-gray-500 group-hover:bg-gray-100 mt-1 mr-1 px-2 py-0.5 rounded-md">{{ $tag->name }}</div>
                            @endforeach
                        </div>
                        @endif

                        <x-ui.user.awards :user="$user" style="full" />

                        @if ($user->company)
                        <p class="my-3"><x-ui.icon name="office-building" class="text-gray-400" /> {{ $user->company }}</p>
                        @endif

                        @if (count($platforms) || $user->homepage)
                        <nav class="social-bookmarks mb-4 flex flex-wrap">
                            @if ($user->homepage)
                            <x-ui.link href="{{ $user->homepage }}" target="_blank" icon="home" class="mr-2">{{ __('profile/user.homepage') }}</x-ui.link>
                            @endif
                            @foreach ($platforms as $platform => $profile_url)
                            <a href="{{ $profile_url }}" target="_blank" class="flex items-center text-pf-midblue hover:text-pf-lightblue mr-2"><x-ui.social-icon :name="$platform" /> <span class="ml-1 inline-block underline">{{ __('profile/user.' . $platform . '_profile') }}</span></a>
                            @endforeach
                        </nav>
                        @endif

                        @if ($user->about)
                            <h3 class="font-semibold mb-1">{{ __('profile/user.about_me') }}</h3>
                            <p class="font-serif font-light">{{ $user->about }}</p>
                        @endif

                        @if ($user->id == auth()->id())
                        <div class="flex flex-col sm:flex-row mt-5 space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-ui.forms.button tag="a" href="{{ route('account.profile.edit') }}" action="inform">{{ __('profile/user.button_edit_profile') }}</x-ui.forms.button>
                            <x-ui.forms.button tag="a" href="{{ route('account.avatar.edit') }}" action="inform">{{ __('profile/user.button_edit_avatar') }}</x-ui.forms.button>
                        </div>
                        @else
                        <div class="mt-5 flex flex-col sm:flex-row">
                            <x-ui.forms.button tag="a" href="{{ route('talk.create.user', ['usernames' => $user->username]) }}">{{ __('profile/user.button_send_message') }}</x-ui.forms.button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card title="{{ __('profile/user.member_in_groups') }}" class="mt-5 sm:mt-10">
            <div class="space-y-4 p-4">
                @if ($member_peergroups->count() > 0)
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ($member_peergroups as $pg)
                    <x-matcher::peergroup.card :pg="$pg" />
                    @endforeach
                </div>
                @else
                <p class="p-4 text-center">{{ __('profile/user.no_memberships') }}</p>
                @endif
            </div>
        </x-ui.card>
    </div>
</x-layout.minimal>
