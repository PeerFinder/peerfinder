<x-matcher::layout.minimal title="{{ __('matcher::peergroup.group_types_title') }}">

<x-ui.card class="p-5 sm:p-10 mt-5">
    <ul class="space-y-10">
    @foreach ($group_types as $group_type)
        <li>
            <h2 class="text-2xl font-semibold">{{ $group_type->title() }}</h2>
            @if ($group_type->reference())
            <p class="text-sm text-gray-600">@lang('matcher::peergroup.group_type_reference'): <x-ui.link href="{{ $group_type->reference() }}" target="_blank">{{ $group_type->reference() }}</x-ui.link></p>                
            @endif
            <p class="mt-1">{{ $group_type->description() }}</p>

            @if ($group_type->groupTypes->count())
            <ul class="space-y-3 mt-3">
                @foreach ($group_type->groupTypes as $sub_type)
                    <li>
                        <h3 class="font-bold">{{ $sub_type->title() }}</h3>
                        @if ($sub_type->reference())
                        <p class="text-sm text-gray-600">@lang('matcher::peergroup.group_type_reference'): <x-ui.link href="{{ $sub_type->reference() }}" target="_blank">{{ $sub_type->reference() }}</x-ui.link></p>                
                        @endif
                        <p class="mt-1">{{ $sub_type->description() }}</p>
                    </li>
                @endforeach
            </ul>
            @endif
        </li>
    @endforeach
    </ul>
</x-ui.card>

</x-matcher::layout.minimal>