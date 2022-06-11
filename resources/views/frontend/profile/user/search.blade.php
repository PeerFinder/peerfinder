<x-layout.minimal title="{{ __('profile/search.title') }}">

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="px-4 sm:p-0">
            <x-ui.h1>{{ __('profile/search.title') }}</x-ui.h1>
        </div>

        <x-ui.card class="p-4 mt-5">
            <x-ui.forms.form method="get" action="{{ route('profile.user.search') }}" autocomplete="off" class="space-x-2 flex sm:w-1/2 my-2 mx-auto">
                <x-ui.forms.input name="search" value="{{ request()->get('search') }}" placeholder="{{ __('profile/search.search_placeholder') }}"></x-ui.forms.input>
                <x-ui.forms.button action="inform">@lang('profile/search.button_search')</x-ui.forms.button>
            </x-ui.forms.form>

            @if ($users && $users->count())
            <div class="mt-4">
                <h2 class="font-semibold text-lg mb-4">{{ __('profile/search.results') }}</h2>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($users as $user)
                    <a class="block col-span-1 hover:bg-gray-50 border rounded-md shadow-sm py-2 px-3" href="{{ $user->profileUrl() }}">
                        <div class="text-center my-4">
                            <x-ui.user.avatar :user="$user" size="80" class="rounded-full inline-block" />
                        </div>

                        <h3 class="font-semibold text-center">
                            {{ $user->name }}
                        </h3>

                        @if ($user->slogan)
                        <p class="text-center text-sm text-gray-500 mt-1">{{ $user->slogan }}</p>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            @if ($users->hasPages())
            <div class="mt-4">{{ $users->links() }}</div>
            @endif
            @elseif (request()->get('search', ''))
            <div class="text-center py-10">{{ __('profile/search.no_users_found') }}</div>
            @endif
        </x-ui.card>
    </div>
</x-layout.minimal>