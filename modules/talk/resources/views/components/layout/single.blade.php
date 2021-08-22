@props(['conversation' => null])

<x-layout.minimal :title="__('talk::talk.conversations_title')">
    <x-ui.card class="sm:mt-10">
        <div class="grid grid-cols-10 min-h-screen">
            <div class="col-span-3 border-r bg-gray-50">
                <div class="bg-white p-4 border-b">
                    {{ __('talk::talk.conversations_title') }}
                </div>

                <x-talk-conversations-list :conversation="$conversation" />
            </div>
    
            <div class="col-span-7">
                {{ $slot }}
            </div>
        </div>
    </x-ui.card>
</x-layout.minimal>