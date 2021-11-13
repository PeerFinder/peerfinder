@props(['pg'])

<a href="{{ $pg->getUrl() }}" class="rounded-md block shadow overflow-hidden">
    <div class="h-full flex flex-col justify-between">
        <div>
            <div>
                <img src="{{ Matcher::getGroupImageLink($pg) }}" alt="{{ $pg->image_alt }}" />
            </div>

            @if ($pg->groupType)
            <div>
                <p class="px-4 mt-4 font-semibold text-pf-midblue">{{ $pg->groupType->title() }}</p>
            </div>
            @endif
            
            <div class="p-4">
                <h2 class="font-bold text-xl inline-flex items-center">@if(!$pg->open)<x-ui.icon name="lock-closed" class="mr-1" />@endif{{ $pg->title }}</h2>
            </div>

            <div class="px-4 pb-4">
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
                @if ($pg->virtual)
                <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
                @else
                <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
                @endif
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $pg->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
            </div>

            @if ($pg->tags)
            <div class="flex flex-wrap px-4 pb-4">
            @foreach ($pg->tags as $tag)
                <div class="bg-gray-50 text-gray-500 mt-1 mr-1 text-sm px-3 py-1 rounded-md">{{ $tag->name }}</div>
            @endforeach
            </div>
            @endif
        </div>

        <div class="py-2 border-t flex items-center px-4">
            @if ($pg && $pg->getMembers()->count() > 0)
            <div class="flex-shrink-0 flex items-center text-sm text-gray-500 py-1.5 px-3 bg-gray-50 rounded-full whitespace-nowrap mr-2">
                <x-ui.icon name="user-group" /><span class="inline-block ml-2">{{ $pg->getMembers()->count() }}</span>
            </div>
            <div class="flex items-center flex-wrap">
                @foreach ($pg->memberships as $membership)
                    @if ($membership->user)
                    <div class="-mr-1 my-1">
                        <x-ui.user.avatar :user="$membership->user" size="30" class="rounded-full border border-white" />
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="py-1 text-sm text-gray-400">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</div>
            @endif
        </div>
    </div>
</a>