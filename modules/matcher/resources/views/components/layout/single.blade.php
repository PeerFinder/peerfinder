@props(['pg' => null])

<x-layout.minimal :title="$pg ? $pg->title : __('matcher::peergroup.new_peergroup_title')">
    <div class="mt-5 sm:mt-10 sm:grid sm:grid-cols-10 gap-5">
        <div class="sm:col-span-7">
            @if ($pg)
            <div class="px-4 sm:p-0">
                <h1 class="text-3xl font-semibold">@if($pg->private)<x-matcher::ui.badge icon="lock-closed" class="bg-yellow-300">{{ __('matcher::peergroup.badge_private') }}</x-matcher::ui.badge> @endif{{ $pg->title }}</h1>

                <div class="mt-4 space-x-5">
                    <x-matcher::ui.user :user="$pg->user" role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
                </div>
            </div>
            @else
            <div class="px-4 sm:p-0">
                <h1 class="text-3xl font-semibold">{{ __('matcher::peergroup.new_peergroup') }}</h1>

                <div class="mt-4 space-x-5">
                    <x-matcher::ui.user :user="auth()->user()" role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
                </div>
            </div>
            @endif

            <div class="mt-5">
                {{ $slot }}
            </div>
        </div>

        <div class="sm:col-span-3 space-y-5">
            <x-matcher::peergroup.meeting-link :pg="$pg" />
            <x-matcher::peergroup.members-list :pg="$pg" />
        </div>
    </div>
</x-layout.minimal>