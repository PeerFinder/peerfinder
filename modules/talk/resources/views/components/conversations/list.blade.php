@if ($conversations->count())
    <ul class="p-2 space-y-2 mb-20">
    @foreach ($conversations as $conv)
        <li class="border rounded-md {{ ($conversation && $conv->identifier == $conversation->identifier) ? 'border-pf-midorange bg-pf-midorange text-white' : 'bg-white hover:bg-gray-50' }}">
            <a href="{{ $conv->getUrl() }}">
                {{--
                @if ($conv->isOwnerPeergroup())
                <div class="text-xs flex items-center border-b px-1">
                    <div class="mr-1 pb-0.5">
                        <x-ui.icon name="user-group" size="w-3 h-3" />
                    </div>
                    <div class="line-clamp-1">{{ $conv->conversationable->title }}</div>
                </div>
                @endif
                --}}
                <div class="p-2 flex items-center overflow-hidden" >
                    <div class="w-10 h-10 shrink-0 relative">
                        @include('talk::partials.avatars', ['users' => Talk::filterUsersForConversation($conv)])
                    </div>
                    <div class="flex-1">
                        <div class="line-clamp-1 font-semibold ml-2">
                            @if ($conv->isUnread())<span class="rounded-full inline-block w-3 h-3 bg-pf-darkorange"></span>@endif
                            {{ $conv->getTitle() }}
                        </div>
                        @if ($conv->replies->first())
                        <div class="line-clamp-2 text-sm ml-2">{{ Str::limit($conv->replies->first()->message, 100) }}</div>
                        @endif
                    </div>
                </div>
            </a>
        </li>
    @endforeach
    </ul>
@else
    <p class="text-center p-4 pb-60">{{ __('talk::talk.no_conversations_yet') }}<p>
@endif
