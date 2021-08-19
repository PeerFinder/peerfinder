@if ($conversations->count())
    <ul class="p-1">
    @foreach ($conversations as $conv)
        <li class="border-b border-gray-600 p-3 {{ ($conversation && $conv->identifier == $conversation->identifier) ? 'bg-blue-500' : '' }}">
            <a href="{{ route('talk.show', ['conversation' => $conv->identifier]) }}">
                <div class="truncate bg-yellow-400 font-bold">
                    @if ($conv->title)
                        {{ $conv->title }}
                    @else
                        {{ Talk::usersAsString(Talk::filterUsers($conv)) }}
                    @endif
                </div>
                <div>x</div>
            </a>
        </li>
    @endforeach
    </ul>
@else
    <p>{{ __('talk::talk.no_conversations_yet') }}<p>
@endif