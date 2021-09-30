<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.appointment_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <div class="p-4">

            <div class="flex">
                <div>
                    <div @class(['bg-red-400 border border-red-400 text-center rounded-md overflow-none shadow-sm', 'bg-gray-400 border-gray-400' => $appointment->isInPast()])>
                        <div class="text-white px-3">
                            {{ EasyDate::fromUTC($appointment->date)->getTranslatedShortMonthName() }}
                        </div>
                        <div class="bg-white text-lg rounded-b-md py-1 font-semibold">
                            {{ EasyDate::fromUTC($appointment->date)->format('d') }}
                        </div>
                    </div>
                </div>

                <div class="ml-5">
                    <h2 class="font-semibold text-lg mb-1">{{ $appointment->subject }}</h2>
                    <div class="bg-gray-50 px-1 rounded-md inline-block">{{ EasyDate::fromUTC($appointment->date)->translatedFormat('d.m.Y') }} / {{ EasyDate::fromUTC($appointment->date)->format('H:i') }}</div>
                </div>
            </div>

            @if ($appointment->details)
            <h3 class="mt-7 font-semibold">{{ __('matcher::peergroup.field_details') }}</h3>
            <p>{{ $appointment->details }}</p>
            @endif

            @if ($appointment->location)
            <h3 class="mt-3 font-semibold">{{ __('matcher::peergroup.field_location') }}</h3>
            <p>{{ $appointment->location }}</p>
            @endif
        </div>
        
        <div class="p-4 border-t">
            <x-matcher::ui.edit-buttons :pg="$pg" tag="a" href="{{ route('matcher.appointments.edit', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}" cancel="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}">
                @can('edit', $pg)
                {{ __('matcher::peergroup.button_edit_appointment') }}
                @endif
            </x-matcher::ui.edit-buttons>
        </div>
    </x-ui.card>
</x-matcher::layout.single>