@props(['pg' => null])

<x-layout.minimal :title="$pg ? $pg->title : __('matcher::peergroup.new_peergroup_title')">
    <div class="mt-10 grid grid-cols-10 gap-5">
        <div class="col-span-7">
            @if ($pg)
            <div>
                <h1 class="text-3xl font-semibold">@if($pg->private)<x-matcher::ui.badge icon="lock-closed" class="bg-yellow-300">{{ __('matcher::peergroup.badge_private') }}</x-matcher::ui.badge> @endif{{ $pg->title }}</h1>

                <div class="mt-4 space-x-5">
                    <x-matcher::ui.user :user="$pg->user" role="{{ __('matcher::peergroup.role_founder') }}" />
                </div>
            </div>
            @endif

            <div class="mt-5">
                {{ $slot }}
            </div>
        </div>
        <div class="col-span-3 space-y-5">
            {{--
            <x-ui.card class="h-32" title="Calender">
                xyz
            </x-ui.card>
            <x-ui.card class="h-32" title="Members">
                abc
            </x-ui.card>
            --}}
        </div>
    </div>
</x-layout.minimal>