<x-ui.card title="{{ __('matcher::peergroup.group_description') }}" edit="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="flex">
        @if ($pg->groupType)
        <div class="w-1/3 flex-shrink-0">
            <div class="m-4 mr-0 p-4 border rounded-md shadow-sm bg-gray-50">
                <div class="text-center mb-2 text-gray-500">
                    <x-ui.icon name="annotation" size="10" />
                </div>
                <h2 class="font-bold mb-2">{{ $pg->groupType->title() }}</h2>
                <p class="text-sm">{{ $pg->groupType->description() }}</p>
            </div>
        </div>
        @endif
        <div>
            <div class="p-4 pb-4">
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_begin') }}" icon="calendar">{{ $pg->begin->format('d.m.y') }}</x-matcher::ui.group-detail>
                @if ($pg->virtual)
                <x-matcher::ui.group-detail icon="desktop-computer">{{ __('matcher::peergroup.detail_virtual_group') }}</x-matcher::ui.group-detail>
                @else
                <x-matcher::ui.group-detail icon="location-marker">{{ $pg->location }}</x-matcher::ui.group-detail>
                @endif
                <x-matcher::ui.group-detail title="{{ __('matcher::peergroup.detail_languages') }}" icon="translate">{{ $pg->languages->implode('title', ', ') }}</x-matcher::ui.group-detail>
            </div>
            <div class="px-4 pt-2 pb-4 prose prose-blue">
                {!! Matcher::renderMarkdown($pg->description) !!}
            </div>
        </div>
    </div>
</x-ui.card>