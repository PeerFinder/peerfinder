<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}
    </x-slot>

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="sm:flex sm:items-center space-y-2 text-center sm:text-left">
            <div class="flex-1 inline-flex items-center mr-2">
                <x-ui.user.avatar :user="auth()->user()" size="40" class="rounded-full block mr-2" />
                <x-ui.h1>{{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}</x-ui.h1>
            </div>
            <div>
                <x-ui.forms.button tag="a" href="{{ route('account.profile.edit') }}" action="inform">{{ __('profile/user.button_edit_profile') }}</x-ui.forms.button>
            </div>
        </div>

        <div class="mt-5 sm:mt-10 grid sm:grid-cols-2 gap-2 sm:gap-5">
            <div class="col-span-1">
                <x-ui.card title="{{ __('dashboard/dashboard.member_in_groups') }}">
                    <div class="space-y-4 p-4">
                        <div class="p-4 py-6 border rounded-md border-dashed text-center">
                            <div class="mb-3"><span class="block text-2xl font-semibold">{{ $all_peergroups_count }}</span> offene Gruppen warten auf Dich!</div>
                            <x-ui.forms.button tag="a" href="{{ route('matcher.index') }}" action="attention">{{ __('dashboard/dashboard.button_find_group') }}</x-ui.forms.button>
                        </div>
                        @forelse ($member_peergroups as $pg)
                        <x-matcher::peergroup.card :pg="$pg" />
                        @empty
                        <p class="p-4 text-center">{{ __('dashboard/dashboard.no_memberships') }}</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
            <div class="col-span-1">
                <x-ui.card title="{{ __('dashboard/dashboard.owned_groups') }}">
                    <div class="space-y-4 p-4">
                        <div class="p-4 py-6 border rounded-md border-dashed text-center">
                            <div class="mb-3"><span class="block text-2xl font-semibold">{{ $users_count }}</span> Nutzer:innen freuen sich auf Deine Gruppe</div>
                            <x-ui.forms.button tag="a" href="{{ route('matcher.create') }}" action="create">{{ __('dashboard/dashboard.button_create_group') }}</x-ui.forms.button>
                        </div>
                        @forelse ($own_peergroups as $pg)
                        <x-matcher::peergroup.card :pg="$pg" />
                        @empty
                        <p class="p-4 text-center">{{ __('dashboard/dashboard.no_owned_groups') }}</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layout.minimal>