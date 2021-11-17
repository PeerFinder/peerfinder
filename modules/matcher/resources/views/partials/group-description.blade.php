<x-ui.card title="{{ __('matcher::peergroup.group_description') }}" edit="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" :can="auth()->check() ? auth()->user()->can('edit', $pg) : false">
    <div>

        <div class="p-4 flex items-stretch gap-4 text-center flex-col md:flex-row">
            <div class="border rounded-md p-2 flex-1 flex flex-col justify-evenly shadow-sm">
                <h3 class="font-semibold mb-2 text-gray-600"><x-ui.icon name="calendar" viewBox="0 3 20 20" /> {{ __('matcher::peergroup.detail_begin') }}</h3>
                <div class="text-xl">{{ $pg->begin->format('d.m.y') }}</div>
            </div>
            <div class="border rounded-md p-2 flex-1 flex flex-col justify-evenly shadow-sm">
                @if ($pg->virtual)
                <h3 class="font-semibold text-gray-600"><x-ui.icon name="desktop-computer" viewBox="0 2 20 20" /> {{ __('matcher::peergroup.detail_virtual_group') }}</h3>
                @else
                <h3 class="font-semibold mb-2 text-gray-600"><x-ui.icon name="location-marker" viewBox="0 2 20 20" /> {{ __('matcher::peergroup.detail_location') }}</h3>
                <div>{{ $pg->location }}</div>
                @endif
            </div>
            <div class="border rounded-md p-2 flex-1 flex flex-col justify-evenly shadow-sm">
                <h3 class="font-semibold mb-2 text-gray-600"><x-ui.icon name="translate" viewBox="0 3 20 20" /> {{ __('matcher::peergroup.detail_languages') }}</h3>
                <div>{{ $pg->languages->implode('title', ', ') }}</div>
            </div>
        </div>

        @if ($pg->groupType)
        <div>
            <div class="mx-4 mb-4 p-4 border rounded-md shadow-sm flex items-center">
                <div class="text-center text-gray-500 mr-4">
                    <x-ui.icon name="annotation" size="w-10 h-10" />
                </div>
                <div>
                    <h2 class="font-bold mb-2 text-gray-600">{{ $pg->groupType->title() }}</h2>
                    <p class="font-serif font-light">
                        {{ $pg->groupType->description() }}
                        @if ($pg->groupType->reference())
                        <x-ui.link href="{{ $pg->groupType->reference() }}" target="_blank">@lang('matcher::peergroup.group_type_reference')</x-ui.link>
                        @endif 
                    </p>
                </div>
            </div>
        </div>
        @endif

        <h3 class="font-bold border-t pt-8 px-8 mb-4 text-gray-600">Beschreibung</h3>

        <div class="px-8 mb-8 font-serif font-light prose prose-blue">
            {!! Matcher::renderMarkdown($pg->description) !!}
        </div>
    </div>
</x-ui.card>