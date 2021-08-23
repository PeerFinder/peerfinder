@props(['conversation' => null])

<x-layout.minimal title="{{ $conversation ? $conversation->getTitle() : __('talk::talk.conversations_title') }}">
    @if ($conversation)
    <div class="block sm:hidden p-3 bg-gray-50 border-b">
        <x-ui.link class="block text-center" href="{{ route('talk.index') }}">All conversations</x-ui.link>
    </div>
    @endif

    <x-ui.card class="sm:mt-10">
        <div class="grid grid-cols-10 min-h-screen">
            <div @class([
                'hidden sm:block col-span-3 border-r bg-gray-50' => $conversation,
                'col-span-10 sm:col-span-3 border-r bg-gray-50' => !$conversation,
            ])>
                <div class="bg-white p-4 border-b">
                    {{ __('talk::talk.conversations_title') }}
                </div>

                <x-talk-conversations-list :conversation="$conversation" />
            </div>
    
            <div @class([
                'col-span-10 sm:col-span-7' => $conversation,
                'hidden sm:block sm:col-span-7' => !$conversation,
            ])>
                {{ $slot }}
            </div>
        </div>
    </x-ui.card>
</x-layout.minimal>