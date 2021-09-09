@can('edit', $pg)
<x-ui.card class="my-5 after:bg-gradient-to-r after:from-red-400 after:to-red-600 after:h-1 after:block overflow-hidden" title="{{ __('matcher::peergroup.group_administration') }}" subtitle="{{ __('matcher::peergroup.group_administration_notice') }}">
    @if ($pending)
        <div class="p-4 border-b">
            <h2 class="mb-2 font-semibold">{{ __('matcher::peergroup.title_waiting_approval') }}</h2>
            
            <div class="space-y-2">
            @foreach ($pending as $pending_membership)
                <div class="bg-gray-50 p-2 flex rounded-md">
                    <div class="flex-1 flex items-center">
                        <x-ui.user.avatar :user="$pending_membership->user" size="30" class="rounded-full mr-2" />
                        <a href="{{ $pending_membership->user->profileUrl() }}">{{ $pending_membership->user->name }}</a>
                    </div>
                    <div>
                        
                        <x-ui.forms.button class="mr-1">{{ __('matcher::peergroup.button_approve_member') }}</x-ui.forms.button>

                        <x-ui.forms.button action="inform">{{ __('matcher::peergroup.button_decline_member') }}</x-ui.forms.button>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    @endif

    <div class="flex justify-between p-4">
        <x-ui.forms.button tag="a" href="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_group') }}</x-ui.forms.button>

        <x-ui.forms.form :action="route('matcher.complete', ['pg' => $pg->groupname])" method="post">
            @csrf
            @if ($pg->isOpen())
            <input name="status" value="1" type="hidden" />
            <x-ui.forms.button action="inform">{{ __('matcher::peergroup.button_complete_group') }}</x-ui.forms.button>
            @else
            <input name="status" value="0" type="hidden" />
            @if ($pg->canUncomplete())
            <x-ui.forms.button action="create">{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @else
            <x-ui.forms.button action="inform" class="text-gray-300 border-gray-200" disabled>{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @endif
            @endif
        </x-ui.forms.form>

        @if ($pg->hasMoreMembersThanOwner())
        <x-ui.forms.button tag="a" href="{{ route('matcher.editOwner', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_transfer_ownership') }}</x-ui.forms.button>
        @endif

        <x-ui.forms.button tag="a" href="{{ route('matcher.delete', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_delete_group') }}</x-ui.forms.button>
    </div>
</x-ui.card>
@endcan
