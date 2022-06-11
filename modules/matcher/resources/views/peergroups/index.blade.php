<x-matcher::layout :infocards="$infocards">

<x-ui.card class="p-4 mt-5">
    @if ($peergroups->count() > 0)
    <div class="md:flex md:gap-4">
        <div class="md:w-1/4 mb-4 md:mb-0">
            @if (Matcher::isAnyFilterSet())
            <x-ui.forms.button tag="a" class="mb-4 w-full" href="{{ route('matcher.index') }}">{{ __('matcher::peergroup.reset_all_filters') }}</x-ui.forms.button>
            @endif

            <collapsed-content break-point="768">
                <template v-slot:trigger>
                    <div class="flex items-center justify-center space-x-2 bg-gray-50 py-1 rounded-md">
                        <x-ui.icon name="filter" />
                        <span>{{ __('matcher::peergroup.filter_groups') }}</span>
                    </div>
                </template>
                <template v-slot:content>
                    <div class="mt-3 md:mt-0 space-y-3">
                        <x-matcher::peergroup.filter-list :filters="$filters" key="groupType" title="{{ __('matcher::peergroup.filter_group_types') }}" />
                        <x-matcher::peergroup.filter-list :filters="$filters" key="language" title="{{ __('matcher::peergroup.filter_languages') }}" />
                        <x-matcher::peergroup.filter-list :filters="$filters" key="virtual" title="{{ __('matcher::peergroup.filter_virtual') }}" />
                        <x-matcher::peergroup.filter-list :filters="$filters" key="tag" title="{{ __('matcher::peergroup.filter_tags') }}" />
                    </div>
                </template>
            </collapsed-content>
        </div>
        <div class="md:flex-1">
                <div class="grid sm:grid-cols-2 gap-4">
                @foreach ($peergroups as $pg)
                    <x-matcher::peergroup.card :pg="$pg" />
                @endforeach
                </div>

                @if ($peergroups->hasPages())
                <div class="mt-4">{{ $peergroups->appends($params)->links() }}</div>
                @endif
        </div>
    </div>
    @else
    <div class="text-center p-10 py-20">
        <div class="text-lg">{{ __('matcher::peergroup.no_groups_yet') }}</div>

        @if (Matcher::isAnyFilterSet())
            <x-ui.forms.button class="mt-5" tag="a" href="{{ route('matcher.index') }}">{{ __('matcher::peergroup.reset_all_filters') }}</x-ui.forms.button>
        @endif
    </div>
    @endif
</x-ui.card>

</x-matcher::layout>