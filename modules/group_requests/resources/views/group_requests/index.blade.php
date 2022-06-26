<x-group_requests::layout.minimal :title="__('group_requests::group_requests.index_title')">
    <x-ui.card class="mt-5" :title="__('group_requests::group_requests.your_requests')">
        @if ($users_group_requests->count())
        @foreach ($users_group_requests as $group_request)
            {{ $group_request->title }}
        @endforeach
        @else
        <div class="p-8 text-center space-y-4">
            <h2>@lang('group_requests::group_requests.you_have_no_requests_yet')</h2>
            <x-ui.forms.button tag="a" action="create" href="{{ route('group_requests.create') }}">{{ __('group_requests::group_requests.create_request') }}</x-ui.forms.button>
        </div>
        @endif
    </x-ui.card>

    @if ($other_group_requests->count())
    <x-ui.card class="mt-5" :title="__('group_requests::group_requests.users_requests')">
        @foreach ($other_group_requests as $group_request)
        {{ $group_request->title }}
        @endforeach
    </x-ui.card>
    @endif
</x-group_requests::layout.minimal>
