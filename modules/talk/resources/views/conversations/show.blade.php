<x-talk::layout.single :conversation="$conversation">
    @if ($conversation->title)
        <p>{{ $conversation->title }}</p>
    @endif
                    
    <p>{{ Talk::usersAsString(Talk::listOfUsers($conversation)) }}</p>
</x-talk::layout.single>