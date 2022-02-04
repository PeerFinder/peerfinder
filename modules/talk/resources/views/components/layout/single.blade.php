@props(['conversation' => null])

<x-layout.minimal title="{{ $conversation ? __('talk::talk.conversation_title', ['title' => $conversation->getTitle()]) : __('talk::talk.conversations_title') }}">
    @if ($conversation)
    <div class="block sm:hidden p-3 bg-gray-50 border-b">
        <x-ui.link class="block text-center" href="{{ route('talk.index') }}">{{ __('talk::talk.all_conversations_link') }}</x-ui.link>
    </div>
    @endif

    <x-ui.card class="sm:mt-10 mb-5 sm:mb-10">
        <div class="grid grid-cols-10">
            <div @class([
                'hidden sm:block col-span-3 border-r bg-gray-50 rounded-l-md' => $conversation,
                'col-span-10 sm:col-span-3 border-r bg-gray-50 rounded-l-md' => !$conversation,
            ])>
                <div class="bg-white border-b p-2 flex justify-between items-center flex-wrap rounded-tl-md">
                    <div class="p-2">
                    {{ __('talk::talk.conversations_title') }}
                    </div>
                    <div>
                        <x-ui.forms.button action="create" tag="a" href="{{ route('talk.select') }}">{{ __('talk::talk.conversations_new') }}</x-ui.forms.button>
                    </div>
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