<x-group_requests::layout.minimal :title="__('group_requests::group_requests.index_title')" :create="true">
    <x-ui.card class="mt-5" :title="__('group_requests::group_requests.your_requests')">
        @if ($users_group_requests->count())
        <div class="p-4 grid lg:grid-cols-3 sm:grid-cols-2 gap-4">
        @foreach ($users_group_requests as $group_request)
            <x-group_requests::request.card :group_request="$group_request" />
        @endforeach
        </div>
        @else
        <div class="p-8 text-center space-y-4">
            <h2 class="mb-2 font-semibold">@lang('group_requests::group_requests.what_do_you_want')</h2>
            <x-ui.link tag="a" href="{{ route('group_requests.create') }}">{{ __('group_requests::group_requests.create_request') }}</x-ui.link>
            <p>@lang('group_requests::group_requests.you_have_no_requests_yet')</p>
        </div>
        @endif
    </x-ui.card>

    @if ($other_group_requests->count())
    <x-ui.card class="mt-5" :title="__('group_requests::group_requests.users_requests')">
        <div class="p-4 grid lg:grid-cols-3 sm:grid-cols-2 gap-4">
        @foreach ($other_group_requests as $group_request)
            <x-group_requests::request.card :group_request="$group_request" />
        @endforeach
        </div>
    </x-ui.card>
    @endif
</x-group_requests::layout.minimal>
