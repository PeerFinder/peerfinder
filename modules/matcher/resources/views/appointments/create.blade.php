<x-matcher::layout.single :pg="$pg">

    <x-ui.card class="my-5" title="{{ __('matcher::peergroup.create_appointment_title') }}">
        <x-ui.errors :errors="$errors" class="p-3 m-4 mb-2" />

        <x-ui.forms.form :action="route('matcher.appointments.store', ['pg' => $pg->groupname])">
            <x-matcher::appointment.edit />

            <div class="mt-2 p-4 border-t">
                @csrf
                @method('PUT')

                <x-matcher::ui.edit-buttons :pg="$pg" action="create" cancel="{{ route('matcher.appointments.index', ['pg' => $pg->groupname]) }}">{{ __('matcher::peergroup.button_create_appointment') }}</x-matcher::ui.edit-buttons>
            </div>
        </x-ui.forms.form>
    </x-ui.card>
</x-matcher::layout.single>