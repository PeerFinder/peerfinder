<x-group_requests::layout.minimal :title="$group_request->title">
    <div class="mt-4 flex items-center space-x-2 px-4 sm:px-0">
        <a href="{{ $group_request->user->profileUrl() }}"><x-ui.user.avatar :user="$group_request->user" size="40" class="rounded-full" /></a>
        <div>
            <span class="text-gray-500 block text-sm">@lang('group_requests::group_requests.submitted_by')</span>
            <div class="font-semibold"><a href="{{ $group_request->user->profileUrl() }}">{{ $group_request->user->name }}</a></div>
        </div>
    </div>

    <x-ui.card class="mt-8" :title="__('group_requests::group_requests.request_details')">
        <div class="sm:flex">
            <div class="flex-1">
                <div class="p-4">
                    {{ $group_request->description }}
                </div>

                <div class="px-4 pb-4">
                    <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $group_request->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
                </div>
            </div>
            @can('edit', $group_request)
            <div class="flex flex-col space-y-2 p-4">
                <x-ui.forms.button tag="a" href="{{ route('group_requests.edit', ['group_request' => $group_request->identifier]) }}" action="inform">{{ __('group_requests::group_requests.button_edit') }}</x-ui.forms.button>
                <x-ui.forms.button tag="a" href="{{ route('group_requests.delete', ['group_request' => $group_request->identifier]) }}" action="destroy">{{ __('group_requests::group_requests.button_delete') }}</x-ui.forms.button>
            </div>                
            @endcan
        </div>
    </x-ui.card>
</x-group_requests::layout.minimal>