<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.appointments_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <div class="p-4 space-y-1">
            @forelse ($appointments as $appointment)
            <div class="border">
                <div>{{ $appointment->date }}</div>
                <div class="bg-red-100">{{ $appointment->date->format('d') }} {{ $appointment->date->getTranslatedShortMonthName() }}</div>
                <div>{{ EasyDate::fromUTC($appointment->time) }}</div>
                <div>{{ $appointment->subject }}</div>
                <div>{{ $appointment->details }}</div>
                <div>{{ $appointment->location }}</div>
            </div>
            @empty
            <p class="p-4 pt-0 text-center">{{ __('matcher::peergroup.no_appointments_yet') }}</p>
            @endforelse
            
            <div class="p-4 text-center border border-dashed rounded-md">
                <x-ui.forms.button action="create" tag="a" href="{{ route('matcher.appointments.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_new_appointment') }}</x-ui.forms.button>
            </div>
        </div>
            
        <div class="p-4 border-t">
            <x-matcher::ui.edit-buttons :pg="$pg" />
        </div>
    </x-ui.card>
</x-matcher::layout.single>