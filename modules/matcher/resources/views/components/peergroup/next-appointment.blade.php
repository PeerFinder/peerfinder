@if ($pg && $pg->isMember() && $pg->appointments->count())
<x-ui.card title="{{ __('matcher::peergroup.caption_next_appointments') }}" 
            subtitle="{{ __('matcher::peergroup.visible_for_members') }}" 
            edit="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="p-2 space-y-2">
        @foreach ($pg->appointments as $appointment)
        <a href="{{ route('matcher.appointments.show', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}" class="p-2 rounded-md block hover:bg-gray-50">
            <div>
                @if ($appointment->isInPast())
                    <div class="text-sm text-center mb-4 text-gray-500">{{ __('matcher::peergroup.appointment_in_past_notice') }}</div>
                @endif
                
                <div class="flex items-center justify-center">
                    <div @class(['bg-red-400 border border-red-400 w-10 text-center rounded-md overflow-none', 'bg-gray-400 border-gray-400' => $appointment->isInPast()])>
                        <div class="text-white text-sm px-1">
                            {{ EasyDate::fromUTC($appointment->date)->getTranslatedShortMonthName() }}
                        </div>
                        <div class="bg-white rounded-b-md font-semibold">
                            {{ EasyDate::fromUTC($appointment->date)->format('d') }}
                        </div>
                    </div>
                    <div class="bg-gray-100 border py-1 px-2 ml-2 rounded-md text-center text-3xl">
                        {{ EasyDate::fromUTC($appointment->date)->format('H:i') }}
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <p>{{ $appointment->subject }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="text-center p-1 text-sm border-t">
        <x-ui.link href="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.all_appointments') }}</x-ui.link>
    </div>    
</x-ui.card>
@endif