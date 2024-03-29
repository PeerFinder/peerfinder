<div class="p-4 space-y-6">
    <div>
        <x-ui.forms.input id="subject" value="{{ old('subject', $appointment->subject) }}" name="subject" type="text" required>{{ __('matcher::peergroup.field_subject') }}</x-ui.forms.input>
    </div>
    <div class="flex">
        <div class="w-1/2">
            <x-ui.forms.input id="date" value="{{ old('date', EasyDate::fromUTC($appointment->date)->format('Y-m-d')) }}" name="date" type="date" required>{{ __('matcher::peergroup.field_date') }}</x-ui.forms.input>
        </div>
        <div class="ml-2">
            <x-ui.forms.input id="time" value="{{ old('time', EasyDate::fromUTC($appointment->date)->format('H:i')) }}" name="time" type="time" required>{{ __('matcher::peergroup.field_time') }}</x-ui.forms.input>
        </div>
    </div>
    <div class="flex">
        <div class="w-1/2">
            <x-ui.forms.input id="end_date" value="{{ old('end_date', EasyDate::fromUTC($appointment->end_date)->format('Y-m-d')) }}" name="end_date" type="date" required>{{ __('matcher::peergroup.field_date_end') }}</x-ui.forms.input>
        </div>
        <div class="ml-2">
            <x-ui.forms.input id="end_time" value="{{ old('end_time', EasyDate::fromUTC($appointment->end_date)->format('H:i')) }}" name="end_time" type="time" required>{{ __('matcher::peergroup.field_time_end') }}</x-ui.forms.input>
        </div>
    </div>
    <div>
        <x-ui.forms.textarea id="details" value="{{ old('details', $appointment->details) }}" name="details" rows="3">{{ __('matcher::peergroup.field_details') }}</x-ui.forms.textarea>
    </div>
    <div>
        <x-ui.forms.input id="location" value="{{ old('location', $appointment->location) }}" name="location" type="text" :disabled="$appointment->peergroup->inherit_location">{{ __('matcher::peergroup.field_location') }}</x-ui.forms.input>
    </div>
</div>