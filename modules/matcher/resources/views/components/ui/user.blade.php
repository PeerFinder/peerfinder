<div class="inline-flex items-center">
    <div class="flex-1 mr-3">
        <a href="{{ $user->profileUrl() }}"><x-ui.user.avatar :user="$user" size="40" class="rounded-full" /></a>
    </div>
    <div>
        <h2 class="font-semibold text-sm"><a href="{{ $user->profileUrl() }}">{{ $user->name }}</a></h2>
        <span class="text-gray-500 block text-sm">{{ $role }}</span>
    </div>
</div>