@props(['pg'])

<a href="{{ $pg->getUrl() }}" class="border rounded-md block shadow-sm bg-gray-50">
    <div class="">
        <div class="p-4 bg-white rounded-t">
            <h2 class="font-semibold inline-flex items-center">@if(!$pg->open)<x-ui.icon name="lock-closed" class="mr-1" />@endif{{ $pg->title }}</h2>
        </div>
        <div>
            <div class="px-4 pb-4 bg-white">
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
                @if ($pg->virtual)
                <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
                @else
                <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
                @endif
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $pg->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
            </div>
            @if ($pg && $pg->getMembers()->count() > 0)
            <div class="border-t px-4 pt-3 text-sm text-gray-500">{{ trans_choice('matcher::peergroup.number_of_members', $pg->getMembers()->count(), ['count' => $pg->getMembers()->count()]) }}</div>
            <div class="px-4 p-2 space-y-1">
                @foreach ($pg->memberships as $membership)
                    @if ($membership->user)
                    <div class="inline-block -mr-1">
                        <x-ui.user.avatar :user="$membership->user" size="30" class="rounded-full" />
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="border-t p-4 text-sm text-gray-400">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</div>
            @endif
        </div>
    </div>
</a>