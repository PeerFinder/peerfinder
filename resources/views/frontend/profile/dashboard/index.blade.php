<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}
    </x-slot>

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="sm:flex sm:items-center space-y-2 text-center sm:text-left">
            <div class="flex-1 inline-flex items-center mr-2">
                <x-ui.user.avatar :user="auth()->user()" size="40" class="rounded-full block mr-2" />
                <x-ui.h1>{{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }} <x-ui.user.awards :user="auth()->user()" style="inline" /></x-ui.h1>
            </div>
            <div>
                <x-ui.forms.button tag="a" href="{{ route('account.profile.edit') }}" action="inform">{{ __('profile/user.button_edit_profile') }}</x-ui.forms.button>
            </div>
        </div>

        {{-- Next appointments --}}
        @if ($appointments)
        <div class="mt-5 sm:mt-10">
            <x-ui.card title="{{ __('dashboard/dashboard.next_appointments') }}">
                <div class="grid grid-cols-4 gap-4 p-4">
                @foreach ($appointments as $appointment)
                    <a href="{{ route('matcher.appointments.show', ['pg' => $appointment->pg->groupname, 'appointment' => $appointment->identifier]) }}" class="col-span-2 md:col-span-1 block hover:bg-gray-50 border rounded-md shadow-sm pb-3">
                        <div class="border-b px-1">
                            <div class="text-xs flex items-center">
                                <div class="mr-1 pb-0.5">
                                    <x-ui.icon name="user-group" size="w-3 h-3" />
                                </div>
                                <div class="line-clamp-1">{{ $appointment->pg->title }}</div>
                            </div>
                        </div>

                        <x-matcher::appointment.details :appointment="$appointment" class="px-4 pt-4" />
                    </a>
                @endforeach
                </div>
            </x-ui.card>
        </div>
        @endif

        {{-- Groups --}}
        <div class="mt-5 sm:mt-10 grid sm:grid-cols-2 gap-2 sm:gap-5">
            <div class="col-span-1">
                <x-ui.card title="{{ __('dashboard/dashboard.member_in_groups') }}">
                    <div class="space-y-4 p-4">
                        <div class="p-4 py-6 border rounded-md border-dashed text-center">
                            <div class="mb-3"><span class="block text-2xl font-semibold">{{ $all_peergroups_count }}</span> {{ trans_choice('dashboard/dashboard.groups_waiting_for_you', $all_peergroups_count) }}</div>
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
                            <div class="mb-3"><span class="block text-2xl font-semibold">{{ $users_count }}</span> {{ trans_choice('dashboard/dashboard.users_waiting_for_your_group', $users_count) }}</div>
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