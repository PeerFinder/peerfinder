<x-ui.card title="{{ __('matcher::peergroup.group_description') }}" edit="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="lg:flex">
        @if ($pg->groupType)
        <div class="lg:w-1/3 flex-shrink-0">
            <div class="m-4 lg:mr-0 p-4 border rounded-md shadow-sm bg-gray-50">
                <div class="text-center mb-2 text-gray-500">
                    <x-ui.icon name="annotation" size="10" />
                </div>
                <h2 class="font-bold mb-2">{{ $pg->groupType->title() }}</h2>
                <p class="text-sm">{{ $pg->groupType->description() }}</p>
                @if ($pg->groupType->reference())
                <p class="text-sm text-center mt-2"><a href="{{ $pg->groupType->reference() }}" target="_blank" class="inline-flex items-center py-1 bg-white text-gray-500 hover:text-pf-midblue px-2 rounded-md"><x-ui.icon name="arrow-circle-right" class="mr-1" /> @lang('matcher::peergroup.group_type_reference')</a></p>
                @endif
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