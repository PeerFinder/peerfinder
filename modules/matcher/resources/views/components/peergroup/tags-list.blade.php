@props(['pg', 'asLinks' => false])

@if ($pg->tags->count())
<div {{ $attributes->merge(['class' => "flex flex-wrap"]) }}>
@foreach ($pg->tags as $tag)
    @if ($asLinks)
    <a href="{{ route('matcher.index', ['tag' => $tag->name]) }}" class="bg-white text-gray-500 hover:bg-pf-midblue hover:text-white mt-1 mr-1 text-sm px-2 py-0.5 rounded-md">{{ $tag->name }}</a>
    @else
    <div class="bg-gray-50 text-gray-500 group-hover:bg-gray-100 mt-1 mr-1 text-sm px-2 py-0.5 rounded-md">{{ $tag->name }}</div>
    @endif
@endforeach
</div>
@endif