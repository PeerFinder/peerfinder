<x-layout.minimal :title="$user->name">
    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <x-ui.card>
            <div class="sm:flex">
                <div class="visual sm:w-1/4">
                    <div class="pt-10 sm:p-10 flex flex-col items-center">
                        <div class="image">
                            <x-ui.user.avatar :user="$user" class="rounded-full text-gray-400" size="200" />
                        </div>
                        @if ($user != auth()->user())
                        <div class="mt-5">
                            <x-ui.forms.button tag="a" href="{{ route('talk.create.user', ['user' => $user->username]) }}">{{ __('profile/user.button_send_message') }}</x-ui.forms.button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="information sm:w-3/4">
                    <div class="p-10 sm:pl-0 sm:pr-10 sm:py-12">
                        <x-ui.h1>{{ $user->name }}</x-ui.h1>

                        @if ($user->slogan)
                        <p class="text-gray-400 mb-4">{{ $user->slogan }}</p>
                        @endif

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
                            <p>{{ $user->about }}</p>
                        @endif

                        @if ($user == auth()->user())
                        <div class="mt-5 space-x-2">
                            <x-ui.forms.button tag="a" href="{{ route('account.profile.edit') }}" action="inform">{{ __('profile/user.button_edit_profile') }}</x-ui.forms.button>
                            <x-ui.forms.button tag="a" href="{{ route('account.avatar.edit') }}" action="inform">{{ __('profile/user.button_edit_avatar') }}</x-ui.forms.button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-layout.minimal>
