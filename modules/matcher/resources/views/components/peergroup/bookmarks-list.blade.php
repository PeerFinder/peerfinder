@if ($pg && $pg->isMember() && $pg->bookmarks->count())
<x-ui.card title="{{ __('matcher::peergroup.caption_bookmarks') }}" 
            subtitle="{{ __('matcher::peergroup.visible_for_members') }}" 
            edit="{{ route('matcher.bookmarks.edit', ['pg' => $pg->groupname]) }}" :can="auth()->user()->can('edit', $pg)">
    <div class="p-4">
        <ul class="space-y-1">
            @foreach ($pg->bookmarks as $bookmark)
            <li>
                <a href="{{ $bookmark->url }}" target="_blank" class="border rounded-md flex items-center p-1 hover:bg-gray-50">
                    <x-ui.icon name="bookmark" class="mr-1 block text-gray-400" /><span class="block truncate">{{ $bookmark->title ?: $bookmark->url }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</x-ui.card>
@endif