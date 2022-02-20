<x-ui.card
    title="{{ trans_choice('matcher::peergroup.number_of_members', $pg->getMembers()->count(), ['count' => $pg->getMembers()->count()]) }}"
    edit="{{ route('matcher.membership.index', ['pg' => $pg->groupname]) }}"
    :can="auth()->check() ? auth()->user()->can('manage-members', $pg) : false">

    @if ($pg->getMembers()->count() > 0)
        <div class="p-4 space-y-1">
            @foreach ($pg->memberships as $membership)
                @if ($membership->user)
                    @if (isset($anonymous) && $anonymous)
                        <div class="border p-2 rounded-md">
                            <x-matcher::ui.user :user="null" />
                        </div>
                    @else
                        <div class="border p-2 rounded-md @if (auth()->id() == $membership->user_id) border-pf-midorange @endif">
                            @if ($pg->user_id == $membership->user_id)
                                <x-matcher::ui.user :user="$membership->user"
                                    role="{{ __('matcher::peergroup.role_founder') }}" />
                            @elseif ($membership->member_role_id)
                                <x-matcher::ui.user :user="$membership->user" :role="$membership->memberRole()" />
                            @else
                                <x-matcher::ui.user :user="$membership->user" />
                            @endif
                            @if ($membership->comment)
                                <div class="bg-gray-50 text-sm mt-2 py-1 px-2 rounded-md">{{ $membership->comment }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    @else
        <p class="p-4">{{ __('matcher::peergroup.this_group_has_no_members_yet') }}</p>
    @endif

    @if (!$pg->isFull())
        @can('create', [Matcher\Models\Invitation::class, $pg])
            <div class="p-4 text-center border-t">
                <x-ui.link href="{{ route('matcher.invitations.create', ['pg' => $pg->groupname]) }}">
                    {{ __('matcher::peergroup.invite_new_members_link') }}</x-ui.link>
            </div>
        @endcan        
    @endif
</x-ui.card>
