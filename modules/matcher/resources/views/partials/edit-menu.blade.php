@can('edit', $pg)
    <dropdown-button>
        <template v-slot:trigger="props">
            <x-ui.forms.button action="inform" vueClick="props.actionToggle" class="w-full">@lang('matcher::peergroup.button_admin_dropdown') <x-ui.icon name="chevron" /></x-ui.forms.button>
        </template>
        <template v-slot:dropdown>
            <div class="bg-red-200">
                <div class="divide-y">
                    @can('complete', $pg)
                    <x-ui.forms.form :action="route('matcher.complete', ['pg' => $pg->groupname])" method="post">
                        @csrf
                        @if ($pg->isOpen())
                        <input name="status" value="1" type="hidden" />
                        <x-ui.forms.dropdown-item>{{ __('matcher::peergroup.button_complete_group') }}</x-ui.forms.dropdown-item>
                        @else
                        <input name="status" value="0" type="hidden" />
                        @if ($pg->canUncomplete())
                        <x-ui.forms.dropdown-item>{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.dropdown-item>
                        @endif
                        @endif
                    </x-ui.forms.form>
                    @endcan
                    <x-ui.forms.dropdown-item href="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_edit_group') }}</x-ui.forms.dropdown-item>
                    <x-ui.forms.dropdown-item href="{{ route('matcher.bookmarks.edit', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_edit_bookmarks') }}</x-ui.forms.dropdown-item>
                    <x-ui.forms.dropdown-item href="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_edit_appointments') }}</x-ui.forms.dropdown-item>
                    @can('manage-members', $pg)
                    <x-ui.forms.dropdown-item href="{{ route('matcher.membership.index', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_manage_members') }}</x-ui.forms.dropdown-item>
                    @endcan
                    @can('edit-owner', $pg)
                    @if ($pg->hasMoreMembersThanOwner())
                    <x-ui.forms.dropdown-item href="{{ route('matcher.editOwner', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_transfer_ownership') }}</x-ui.forms.dropdown-item>
                    @endif
                    @can('delete', $pg)
                    <x-ui.forms.dropdown-item href="{{ route('matcher.delete', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_delete_group') }}</x-ui.forms.dropdown-item>
                    @endcan
                @endcan
                </div>
            </div>
        </template>
    </dropdown-button>
@endcan

{{-- @can('edit', $pg)
<x-ui.card class="after:bg-gradient-to-r after:from-red-400 after:to-red-600 after:h-1 after:block overflow-hidden" title="{{ __('matcher::peergroup.group_administration') }}" subtitle="{{ __('matcher::peergroup.group_administration_notice') }}">
    <div class="p-4 sm:p-4 space-y-2 flex flex-col">
        <x-ui.forms.button tag="a" href="{{ route('matcher.bookmarks.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_bookmarks') }}</x-ui.forms.button>
        
        <x-ui.forms.button tag="a" href="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_appointments') }}</x-ui.forms.button>

        @can('complete', $pg)
        <x-ui.forms.form :action="route('matcher.complete', ['pg' => $pg->groupname])" method="post">
            @csrf
            @if ($pg->isOpen())
            <input name="status" value="1" type="hidden" />
            <x-ui.forms.button action="inform" class="w-full">{{ __('matcher::peergroup.button_complete_group') }}</x-ui.forms.button>
            @else
            <input name="status" value="0" type="hidden" />
            @if ($pg->canUncomplete())
            <x-ui.forms.button action="create" class="w-full">{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @else
            <x-ui.forms.button action="inform" class="w-full text-gray-300 border-gray-200" disabled>{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.button>
            @endif
            @endif
        </x-ui.forms.form>
        @endcan

        @can('manage-members', $pg)
        <x-ui.forms.button tag="a" href="{{ route('matcher.membership.index', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_manage_members') }}</x-ui.forms.button>
        @endcan

        <x-ui.forms.button tag="a" href="{{ route('matcher.edit', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_edit_group') }}</x-ui.forms.button>
        
        @can('edit-owner', $pg)
            @if ($pg->hasMoreMembersThanOwner())
            <x-ui.forms.button tag="a" href="{{ route('matcher.editOwner', ['pg' => $pg->groupname]) }}" action="inform">{{ __('matcher::peergroup.button_transfer_ownership') }}</x-ui.forms.button>
            @endif
        @endcan

        @can('delete', $pg)
        <x-ui.forms.button tag="a" href="{{ route('matcher.delete', ['pg' => $pg->groupname]) }}" action="destroy">{{ __('matcher::peergroup.button_delete_group') }}</x-ui.forms.button>
        @endcan
    </div>
</x-ui.card>
@endcan --}}
