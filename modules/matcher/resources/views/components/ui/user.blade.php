@props(['role' => null, 'user'])

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <div class="mr-3">
        <a href="{{ $user->profileUrl() }}"><x-ui.user.avatar :user="$user" size="40" class="rounded-full" /></a>
    </div>
    <div>
        <h2 class="font-semibold text-sm"><a href="{{ $user->profileUrl() }}">{{ $user->name }}</a></h2>
        @if ($role)
        <span class="text-gray-500 block text-sm">{{ $role }}</span>
        @endif
    </div>
</div>