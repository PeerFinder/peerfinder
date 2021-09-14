<x-ui.card title="{{ __('matcher::peergroup.caption_members_list') }}">
    @if ($pg && $pg->getMembers()->count() > 0)
    <div class="space-y-1 m-2">
        @foreach ($pg->memberships as $membership)
        <div class="border p-2 rounded-md @if (auth()->id() == $membership->user_id) border border-pf-midorange @endif">
            <x-matcher::ui.user :user="$membership->user" />
            @if ($membership->comment)
            <div class="bg-gray-50 text-sm mt-2 py-1 px-2 rounded-md">{{ $membership->comment }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p class="p-4">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</p>
    @endif
</x-ui.card>