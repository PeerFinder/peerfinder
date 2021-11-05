@props(['title', 'filter'])

@if ($filter)
<div>
    <h2 class="font-semibold mb-1">{{ $title }}</h2>
    <ul>
        @foreach ($filter as $p => $f)
            <li><a href="{{ $f['link'] }}" @class(["text-pf-midblue hover:text-pf-lightblue", "font-bold" => $f['active']])>{{ $f['title'] }}</a> <span class="text-gray-400">({{ $f['count'] }})</span></li>
        @endforeach
    </ul>
</div>
@endif
