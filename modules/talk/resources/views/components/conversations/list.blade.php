@if ($conversations->count())
    <ul class="p-2 space-y-2">
    @foreach ($conversations as $conv)
        <li class="border rounded-md {{ ($conversation && $conv->identifier == $conversation->identifier) ? 'border-pf-midorange bg-pf-midorange text-white' : 'bg-white hover:bg-gray-50' }}">
            <a class="p-2 flex items-center" href="{{ $conv->getUrl() }}">
                <div class="w-10 h-10 flex-none relative">
                    <x-talk::conversations.avatars :users="Talk::filterUsersForConversation($conv)" />
                </div>
                <div class="flex-1">
                    <div class="line-clamp-1 font-semibold ml-2">
                        @if ($conv->isUnread())<span class="rounded-full inline-block w-3 h-3 bg-pf-darkorange"></span>@endif
                        {{ $conv->getTitle() }}
                    </div>
                    @if ($conv->replies->first())
                    <div class="line-clamp-2 text-sm ml-2">{{ $conv->replies->first()->message }}</div>
                    @endif
                </div>
            </a>
        </li>
    @endforeach
    </ul>
@else
    <p class="text-center mt-10">{{ __('talk::talk.no_conversations_yet') }}<p>
@endif
