<x-matcher::layout>

<x-ui.card class="p-4 mt-5">
    <div class="md:flex">
        <div class="md:w-1/4 mb-4 md:mb-0">
            <collapsed-content break-point="768">
                <template v-slot:trigger>
                    <div class="flex items-center justify-center space-x-2 bg-gray-50 py-1 rounded-md">
                        <x-ui.icon name="filter" />
                        <span>{{ __('matcher::peergroup.filter_groups') }}</span>
                    </div>
                </template>
                <template v-slot:content>
                    <div class="mt-3 md:mt-0 md:mr-4 space-y-3">
                        <x-matcher::peergroup.filter-list :filters="$filters" key="groupType" title="{{ __('matcher::peergroup.filter_group_types') }}" />
                        <x-matcher::peergroup.filter-list :filters="$filters" key="language" title="{{ __('matcher::peergroup.filter_languages') }}" />
                        <x-matcher::peergroup.filter-list :filters="$filters" key="virtual" title="{{ __('matcher::peergroup.filter_virtual') }}" />
                    </div>
                </template>
            </collapsed-content>
        </div>
        <div class="md:flex-1">
            @if ($peergroups->count() > 0)
            <div class="grid sm:grid-cols-2 gap-4">
            @foreach ($peergroups as $pg)
                <x-matcher::peergroup.card :pg="$pg" />
            @endforeach
            </div>
            @else
                <div class="text-center p-10">{{ __('matcher::peergroup.no_groups_yet') }}</div>
            @endif
        </div>
    </div>

</x-ui.card>

</x-matcher::layout>