<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}
    </x-slot>

    <div class="mt-5 sm:mt-10 flex items-center">
        <x-ui.user.avatar :user="auth()->user()" size="40" class="rounded-full block mr-2" />
        <h1 class="text-3xl font-semibold">{{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}</h1>
    </div>

    <div class="mt-5 sm:mt-10 grid sm:grid-cols-2 gap-2 sm:gap-5">
        <div class="col-span-1">
            <x-ui.card title="{{ __('dashboard/dashboard.owned_groups') }}">
                <div class="space-y-2 p-2">
                    @forelse ($own_peergroups as $pg)
                    <x-peergroup.card :pg="$pg" />
                    @empty
                    <p class="p-4 text-center">{{ __('dashboard/dashboard.no_owned_groups') }}</p>
                    <p class="pb-4 px-4 text-center">
                        <x-ui.forms.button tag="a" href="{{ route('matcher.create') }}" action="create">{{ __('dashboard/dashboard.button_create_group') }}</x-ui.forms.button>
                    </p>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
        <div class="col-span-1">
            <x-ui.card title="{{ __('dashboard/dashboard.member_in_groups') }}">
                <div class="space-y-2 p-2">
                    @forelse ($member_peergroups as $pg)
                    <x-peergroup.card :pg="$pg" />
                    @empty
                    <p class="p-4 text-center">{{ __('dashboard/dashboard.no_memberships') }}</p>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>



</x-layout.minimal>