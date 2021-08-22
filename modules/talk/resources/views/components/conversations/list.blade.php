@if ($conversations->count())
    <ul class="p-2 space-y-2">
    @foreach ($conversations as $conv)
        <li class="p-3 border rounded-md {{ ($conversation && $conv->identifier == $conversation->identifier) ? 'border-pf-midorange bg-pf-midorange text-white' : 'bg-white' }}">
            <a href="{{ route('talk.show', ['conversation' => $conv->identifier]) }}">
                <div class="truncate font-bold">
                    @if ($conv->isUnread())<span class="rounded-full inline-block w-3 h-3 bg-pf-darkorange"></span>@endif
                    @if ($conv->title)
                        {{ $conv->title }}
                    @else
                        {{ Talk::usersAsString(Talk::filterUsersForConversation($conv)) }}
                    @endif
                </div>
                @if ($conv->replies->first())
                <div class="line-clamp-2 text-sm">{{ $conv->replies->first()->message }}</div>
                @endif
            </a>
        </li>
    @endforeach
    </ul>
@else
    <p class="text-center mt-10">{{ __('talk::talk.no_conversations_yet') }}<p>
@endif
