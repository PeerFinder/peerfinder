@props(['role' => null, 'user' => null])

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <div class="mr-3">
        @if ($user)
        <a href="{{ $user->profileUrl() }}"><x-ui.user.avatar :user="$user" size="40" class="rounded-full" /></a>
        @else
        <x-ui.user.avatar size="40" class="rounded-full" />
        @endif
    </div>
    <div>
        @if ($user)
        <h2 class="font-semibold text-sm"><a href="{{ $user->profileUrl() }}">{{ $user->name }}</a> <x-ui.user.awards :user="$user" style="inline" /></h2>
        @else
        <h2 class="font-semibold text-sm">{{ __('matcher::peergroup.preview_only_visible_for_users') }}</h2>
        @endif
        @if ($role)
        <span class="text-gray-500 block text-sm">{{ $role }}</span>
        @endif
    </div>
</div>