<x-ui.card title="{{ __('matcher::peergroup.caption_members_list') }}">
    @if ($pg && $pg->getMembers()->count() > 0)
    <div class="space-y-1 m-2">
        @foreach ($pg->getMembers() as $member)
        <div class="shadow-sm p-2 rounded-md @if (auth()->id() == $member->id) border border-pf-midorange @endif">
            <x-matcher::ui.user :user="$member" />
        </div>
        @endforeach
    </div>
    @else
    <p class="p-4">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</p>
    @endif
</x-ui.card>