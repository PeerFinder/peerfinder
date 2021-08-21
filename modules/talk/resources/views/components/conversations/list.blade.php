@if ($conversations->count())
    <ul class="p-1">
    @foreach ($conversations as $conv)
        <li class="border-b border-gray-600 p-3 {{ ($conversation && $conv->identifier == $conversation->identifier) ? 'bg-blue-100' : '' }}">
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
                <div class="line-clamp-2 text-sm text-gray-600">{{ $conv->replies->first()->message }}</div>
                @endif
            </a>
        </li>
    @endforeach
    </ul>
@else
    <p>{{ __('talk::talk.no_conversations_yet') }}<p>
@endif
