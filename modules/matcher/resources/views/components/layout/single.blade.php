@props(['pg' => null, 'edit' => false])

<x-layout.minimal :title="$pg ? $pg->title : __('matcher::peergroup.new_peergroup_title')">

    @if ($pg)
        @if (!$edit)
        <div class="sm:mt-10 md:grid md:grid-cols-10 gap-7 mb-5 sm:mb-7">
            <div class="sm:col-span-6 lg:col-span-7 relative">
                <a href="{{ $pg->getUrl() }}">
                    <img src="{{ Matcher::getGroupImageLink($pg) }}" alt="{{ $pg->image_alt }}" class="sm:rounded-md" />
                </a>
                @can('edit', $pg)
                <div class="absolute right-3 bottom-2">
                    <x-ui.forms.button tag="a" href="{{ route('matcher.image.edit', ['pg' => $pg->groupname]) }}" action="inform" class="shadow">{{ __('matcher::peergroup.button_edit_image') }}</x-ui.forms.button>
                </div>
                @endcan
            </div>
            <div class="sm:col-span-4 lg:col-span-3 space-y-5 sm:space-y-7 mt-5 md:mt-0 px-4 sm:px-0">
                @if ($pg->groupType)
                <p class="bg-pf-midblue text-white inline-block px-3 py-1 rounded-md mb-3">{{ $pg->groupType->title() }}</p>
                @endif
                
                <a href="{{ $pg->getUrl() }}">
                    <x-ui.h1>
                        {{ $pg->title }}
                    </x-ui.h1>
                    @if($pg->private)<x-matcher::ui.badge icon="eye-off" class="bg-purple-400 mt-2">{{ __('matcher::peergroup.badge_private') }}</x-matcher::ui.badge>@endif
                    @if(!$pg->open)<x-matcher::ui.badge icon="lock-closed" class="bg-yellow-400 mt-2">{{ __('matcher::peergroup.badge_closed') }}</x-matcher::ui.badge>@endif
                </a>

                <div class="mt-7 space-x-5">
                    <x-matcher::ui.user :user="$pg->user" role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
                </div>
            </div>
        </div>
        @else
        <div class="sm:mt-10 mb-5 sm:mb-7">
            <a href="{{ $pg->getUrl() }}">
                <x-ui.h1>
                    {{ $pg->title }}
                </x-ui.h1>
            </a>
        </div>
        @endif
    @else
    <div class="px-4 sm:p-0 mt-5 mb-5 sm:mb-7 sm:mt-10">
        <x-ui.h1>{{ __('matcher::peergroup.new_peergroup') }}</x-ui.h1>

        <div class="mt-4 space-x-5">
            <x-matcher::ui.user :user="auth()->user()" role="{{ __('matcher::peergroup.role_founder') }}" class="inline-flex" />
        </div>
    </div>
    @endif

    <div class="sm:grid sm:grid-cols-10 gap-7 pb-10">
        <div class="sm:col-span-6 lg:col-span-7">
            <div class="space-y-5 sm:space-y-7">
                {{ $slot }}
            </div>
        </div>

        <div class="sm:col-span-4 lg:col-span-3 space-y-5 sm:space-y-7 mt-5 sm:mt-0">
            @include('matcher::partials.next-appointment')
            @include('matcher::partials.meeting-link')
            @include('matcher::partials.bookmarks-list')
            @include('matcher::partials.members-list')
            @include('matcher::partials.edit-menu')
        </div>
    </div>
</x-layout.minimal>