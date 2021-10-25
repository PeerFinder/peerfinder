<x-matcher::layout.single :pg="$pg">

    <x-ui.card title="{{ __('matcher::peergroup.appointments_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <div class="p-4 space-y-1">
            @can('edit', $pg)
            <div class="p-4 text-center border border-dashed rounded-md">
                <x-ui.forms.button action="create" tag="a" href="{{ route('matcher.appointments.create', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_new_appointment') }}</x-ui.forms.button>
            </div>
            @endcan

            @forelse ($appointments as $appointment)
            <div class="bg-gray-50 border p-2 rounded-md flex items-center">
                <div>
                    <div @class(['bg-red-400 border border-red-400 w-10 text-center rounded-md overflow-none shadow-sm', 'bg-gray-400 border-gray-400' => $appointment->isInPast()])>
                        <div class="text-white text-sm px-1">
                            {{ EasyDate::fromUTC($appointment->date)->getTranslatedShortMonthName() }}
                        </div>
                        <div class="bg-white rounded-b-md font-semibold">
                            {{ EasyDate::fromUTC($appointment->date)->format('d') }}
                        </div>
                    </div>
                    <div class="bg-white mt-1 rounded-md text-sm text-center shadow-sm">
                        {{ EasyDate::fromUTC($appointment->date)->format('H:i') }}
                    </div>
                </div>
                <div class="mx-3 flex-1">
                    <a href="{{ route('matcher.appointments.show', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}">
                        <h2 class="line-clamp-1">{{ $appointment->subject }}</h2>
                        @if ($appointment->details)
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $appointment->details }}</p>
                        @endif
                    </a>
                </div>

                @can('edit', $pg)
                <div class="flex items-center bg-white px-2 pb-1 rounded-md space-x-4 shadow-sm">
                    <a class="text-gray-400 hover:text-gray-500" href="{{ route('matcher.appointments.edit', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}"><x-ui.icon name="pencil-alt" /></a>
                    
                    <form action="{{ route('matcher.appointments.destroy', ['pg' => $pg->groupname, 'appointment' => $appointment->identifier]) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <button class="text-red-400 hover:text-red-800"><x-ui.icon name="trash" /></button>
                    </form>
                </div>
                @endcan
            </div>
            @empty
            <p class="p-4 text-center">{{ __('matcher::peergroup.no_appointments_yet') }}</p>
            @endforelse
        </div>
            
        <div class="p-4 border-t">
            <x-matcher::ui.edit-buttons :pg="$pg" />
        </div>
    </x-ui.card>
</x-matcher::layout.single>