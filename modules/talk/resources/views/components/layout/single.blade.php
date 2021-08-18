@props(['conversation' => null])

<x-layout.minimal :title="__('talk::talk.conversations_title')">
    <x-ui.card class="sm:mt-10">
        <div class="grid grid-cols-10">
            <div class="col-span-3 bg-yellow-200">
                <x-talk-conversations-list :conversation="$conversation" />
            </div>
    
            <div class="col-span-7 bg-green-100">
                {{ $slot }}
            </div>
        </div>

    </x-ui.card>
</x-layout.minimal>