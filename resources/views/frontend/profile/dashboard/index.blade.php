<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}
    </x-slot>

    <div class="mt-5 sm:mt-10">
        <h1 class="text-3xl font-semibold">{{ __('dashboard/dashboard.title', ['name' => auth()->user()->name]) }}</h1>
    </div>

    <div class="mt-5 sm:mt-10 grid grid-cols-2 gap-5">
        <div class="col-span-1">
            <x-ui.card title="Own groups">
                <div class="space-y-2 p-2">
                    @forelse ($own_peergroups as $pg)
                    <x-peergroup.card :pg="$pg" />
                    @empty
                    You don't own any groups.
                    @endforelse
                </div>
            </x-ui.card>
        </div>
        <div class="col-span-1">
            <x-ui.card title="Member in groups">
                <div class="space-y-2 p-2">
                    @forelse ($member_peergroups as $pg)
                    <x-peergroup.card :pg="$pg" />
                    @empty
                    You are not a member of any group.
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>



</x-layout.minimal>