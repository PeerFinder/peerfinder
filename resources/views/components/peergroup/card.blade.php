@props(['pg'])

<a href="{{ $pg->getUrl() }}" class="block hover:bg-gray-50">
    <div class="border rounded-md p-4">
        <div class="">
            <h2 class="font-semibold">{{ $pg->title }}</h2>
        </div>
        <div>
            @if ($pg && $pg->getMembers()->count() > 0)
            <div class="mt-2 space-y-1">
                @foreach ($pg->memberships as $membership)
                    @if ($membership->user)
                    <div class="inline-block -mr-1">
                        <x-ui.user.avatar :user="$membership->user" size="30" class="rounded-full" />
                    </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</a>