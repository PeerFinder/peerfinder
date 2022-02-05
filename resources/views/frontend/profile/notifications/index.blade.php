<x-layout.minimal>
    <x-slot name="title">
        {{ __('notifications/notifications.title') }}
    </x-slot>

    <div class="mt-5 sm:mt-10 mb-5 sm:mb-10">
        <div class="sm:flex sm:items-center space-y-2 text-center sm:text-left">
            <x-ui.h1>{{ __('notifications/notifications.title') }}</x-ui.h1>
        </div>

        <x-ui.card class="mt-5 p-4">
            <div class="space-y-2">
                @forelse ($notifications as $notification)
                <a href="{{ $notification['url'] }}" class="border block rounded-md hover:bg-gray-50 shadow-sm">
                    <div class="p-4 flex items-center space-x-4">
                        <div>
                            @if (key_exists('by_user', $notification))
                            <x-ui.user.avatar :user="$notification['by_user']" size="40" class="rounded-full" />
                            @endif
                        </div>
                        <div>
                            @if ($notification['unread'])
                            <h2 class="font-bold"><span class="rounded-full inline-block w-3 h-3 bg-pf-darkorange"></span> {{ $notification['title'] }}</h2>
                            <p class="text-black">{{ $notification['details'] }}</p>
                            @else
                            <h2 class="font-semibold text-gray-400">{{ $notification['title'] }}</h2>
                            <p class="text-gray-400">{{ $notification['details'] }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                    <p class="text-center">{{ __('notifications/notifications.no_notifications_yet') }}</p>
                @endforelse
            </div>
            @if ($links)
            <div class="mt-4">{!! $links !!}</div>
            @endif
        </x-ui.card>
    </div>
</x-layout.minimal>