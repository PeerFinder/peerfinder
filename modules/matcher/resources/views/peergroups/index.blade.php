<x-matcher::layout>

<x-ui.card class="p-4 mt-5">
    @if ($peergroups->count() > 0)
        <div class="grid sm:grid-cols-2 gap-2">
        @foreach ($peergroups as $pg)
            <x-matcher::peergroup.card :pg="$pg" />
        @endforeach
        </div>
    @else
        <div class="text-center p-10">{{ __('matcher::peergroup.no_groups_yet') }}</div>
    @endif
</x-ui.card>

</x-matcher::layout>