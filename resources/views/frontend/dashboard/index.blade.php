<x-layout.minimal>
    <x-slot name="title">
        {{ __('dashboard/dashboard.title') }}
    </x-slot>
    <h1 class="text-3xl my-5">{{ __('dashboard/dashboard.title') }}</h1>

    <div class="grid grid-cols-10">
        <div class="col-span-7">{!! $conversation !!}</div>
        <div class="col-span-3">
            <div class="ml-3 bg-gray-50">Sidebar</div>
        </div>
    </div>
    

</x-layout.minimal>