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
                        @else
                        <x-ui.forms.dropdown-item disabled="true">{{ __('matcher::peergroup.button_uncomplete_group') }}</x-ui.forms.dropdown-item>
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