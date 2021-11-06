@props(['title', 'filters', 'key'])

@if ($filters && $filters[$key])
<div class="bg-gray-50 p-2 rounded-md">
    <h2 class="font-semibold mb-1 bg-white px-2 py-1 rounded-md flex items-center justify-between text-gray-500">
        <div>{{ $title }}</div>
        @if (Matcher::getResetFilterLink($key))
        <a href="{{ Matcher::getResetFilterLink($key) }}" class="inline-flex items-center text-pf-midblue" title="@lang('matcher::peergroup.reset_filter')"><x-ui.icon name="x-circle" /></a>
        @endif
    </h2>

    <ul class="px-1 mt-2 space-y-1">
        @foreach ($filters[$key] as $p => $f)
            <li><a href="{{ $f['link'] }}" @class(["text-pf-midblue hover:text-pf-lightblue", "font-bold" => $f['active']])>{{ $f['title'] }}</a> <span class="text-gray-400">({{ $f['count'] }})</span></li>
        @endforeach
    </ul>
</div>
@endif
