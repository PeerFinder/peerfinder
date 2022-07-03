<x-group_requests::layout.minimal :title="$group_request->title">
    <div class="mt-4 flex items-center space-x-2">
        <a href="{{ $group_request->user->profileUrl() }}"><x-ui.user.avatar :user="$group_request->user" size="40" class="rounded-full" /></a>
        <div>
            <span class="text-gray-500 block text-sm">@lang('group_requests::group_requests.submitted_by')</span>
            <div class="font-semibold"><a href="{{ $group_request->user->profileUrl() }}">{{ $group_request->user->name }}</a></div>
        </div>
    </div>

    <x-ui.card class="mt-8" :title="__('group_requests::group_requests.request_details')">
        <div class="p-4">
            {{ $group_request->description }}
        </div>

        <div class="px-4 pb-4">
            <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $group_request->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
        </div>
    </x-ui.card>
</x-group_requests::layout.minimal>