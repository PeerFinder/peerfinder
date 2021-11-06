<x-matcher::layout>

<x-ui.card class="p-4 mt-5">
    <div class="flex">
        <div class="w-1/4 space-y-3">
            <x-matcher::peergroup.filter-list :filter="$filters['groupType']" title="Group type" />
            <x-matcher::peergroup.filter-list :filter="$filters['language']" title="Language" />
            <x-matcher::peergroup.filter-list :filter="$filters['virtual']" title="Virtual" />
        </div>
        <div class="flex-1">
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