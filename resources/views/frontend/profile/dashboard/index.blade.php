<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}
    </x-slot>

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="flex items-center">
            <x-ui.user.avatar :user="auth()->user()" size="40" class="rounded-full block mr-2" />
            <h1 class="text-3xl font-semibold">{{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}</h1>
        </div>

        <div class="mt-5 sm:mt-10 grid sm:grid-cols-2 gap-2 sm:gap-5">
            <div class="col-span-1">
                <x-ui.card title="{{ __('dashboard/dashboard.owned_groups') }}">
                    <div class="space-y-2 p-2">
                        <p class="p-4 py-6 border rounded-md border-dashed text-center">
                            <x-ui.forms.button tag="a" href="{{ route('matcher.create') }}" action="create">{{ __('dashboard/dashboard.button_create_group') }}</x-ui.forms.button>
                        </p>
                        @forelse ($own_peergroups as $pg)
                        <x-matcher::peergroup.card :pg="$pg" />
                        @empty
                        <p class="p-4 text-center">{{ __('dashboard/dashboard.no_owned_groups') }}</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
            <div class="col-span-1">
                <x-ui.card title="{{ __('dashboard/dashboard.member_in_groups') }}">
                    <div class="space-y-2 p-2">
                        <p class="p-4 py-6 border rounded-md border-dashed text-center">
                            <x-ui.forms.button tag="a" href="{{ route('matcher.index') }}">{{ __('dashboard/dashboard.button_find_group') }}</x-ui.forms.button>
                        </p>
                        @forelse ($member_peergroups as $pg)
                        <x-matcher::peergroup.card :pg="$pg" />
                        @empty
                        <p class="p-4 text-center">{{ __('dashboard/dashboard.no_memberships') }}</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layout.minimal>