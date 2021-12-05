@props(['appointment'])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center'])}}>
    <div @class(['bg-red-400 border border-red-400 w-10 text-center rounded-md overflow-none', 'bg-gray-400 border-gray-400' => $appointment->isInPast()])>
        <div class="text-white text-sm px-1">
            {{ EasyDate::fromUTC($appointment->date)->getTranslatedShortMonthName() }}
        </div>
        <div class="bg-white rounded-b-md font-semibold">
            {{ EasyDate::fromUTC($appointment->date)->format('d') }}
        </div>
    </div>
    <div @class(["border py-1 px-2 ml-2 rounded-md text-center text-3xl", 'bg-green-100 border-green-200' => $appointment->isNow(), 'bg-gray-100' => !$appointment->isNow()])>
        {{ EasyDate::fromUTC($appointment->date)->format('H:i') }}
    </div>
</div>

<div class="mt-3 text-center">
    <p class="font-semibold">{{ $appointment->subject }}</p>
</div>

@if ($appointment->location)
<div class="mt-3 text-center">
    <p class="px-3 text-gray-600 whitespace-nowrap overflow-ellipsis overflow-hidden"><x-ui.icon name="location-marker" viewBox="0 2 20 20" /> {{ $appointment->location }}</p>
</div>
@endif