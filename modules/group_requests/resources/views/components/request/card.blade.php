@props(['group_request'])



<a href="{{ $group_request->getUrl() }}" class="rounded-md block shadow hover:shadow-md overflow-hidden group">
    <div class="flex">
        <div class="">
            <div class="p-4">
                <h2 class="font-bold text-xl inline-flex items-center text-gray-600 group-hover:text-black">
                    {{ $group_request->title }}
                </h2>
            </div>

            <div class="p-4">
                {{ $group_request->description }}
            </div>

            <div class="px-4 pb-4">
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $group_request->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
            </div>
        </div>
    </div>

    <div class="p-4 flex items-center space-x-2">
        <x-ui.user.avatar :user="$group_request->user" size="40" class="rounded-full" /><div class="font-semibold">{{ $group_request->user->name }}</div>
    </div>
</a>