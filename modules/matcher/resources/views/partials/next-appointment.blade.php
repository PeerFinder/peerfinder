@if ($pg && $pg->isMember() && $pg->appointments->count())
<x-ui.card title="{{ __('matcher::peergroup.caption_next_appointments') }}" 
            subtitle="{{ __('matcher::peergroup.visible_for_members') }}" 
            edit="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="p-2 space-y-2">
        @foreach ($pg->appointments as $appointment)
        <a href="{{ route('matcher.appointments.show', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}" class="p-2 rounded-md block hover:bg-gray-50">
            <div>
                <x-matcher::appointment.details :appointment="$appointment" />
            </div>
        </a>
        @endforeach
    </div>
    <div class="text-center p-4 border-t">
        <x-ui.link href="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.all_appointments') }}</x-ui.link>
    </div>    
</x-ui.card>
@endif