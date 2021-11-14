@props(['title', 'filters', 'key'])

@if ($filters && $filters[$key])
<div class="bg-gray-50 p-2 rounded-md">
    <h2 class="font-semibold mb-1 flex items-center justify-between text-gray-500">
        <div>{{ $title }}</div>
        @if (Matcher::getResetFilterLink($key))
        <a href="{{ Matcher::getResetFilterLink($key) }}" class="inline-flex items-center text-pf-midblue hover:text-red-600" title="@lang('matcher::peergroup.reset_filter')"><x-ui.icon name="x-circle" /></a>
        @endif
    </h2>

    {{-- Old layout --}}
    {{--
    <ul class="px-1 mt-2 space-y-1">
        @foreach ($filters[$key] as $p => $f)
            <li><a href="{{ $f['link'] }}" @class(["text-pf-midblue hover:text-pf-lightblue", "font-bold" => $f['active']])>{{ $f['title'] }}</a> <span class="text-gray-400">({{ $f['count'] }})</span></li>
        @endforeach
    </ul>
    --}}

    <ul class="mt-2 flex flex-wrap">
        @foreach ($filters[$key] as $p => $f)
            <li><a href="{{ $f['link'] }}" @class(["flex space-x-2 bg-white py-0.5 pl-2 pr-0.5 my-0.5 mr-1 hover:bg-pf-midblue hover:text-white rounded-md", "text-pf-midblue" => !$f['active'], "bg-pf-midblue text-white" => $f['active']])><div class="line-clamp-1 hyphens">{{ $f['title'] }}</div><div class="text-gray-400 bg-gray-50 px-2 rounded-md">{{ $f['count'] }}</div></a></li>
        @endforeach
    </ul>

</div>
@endif
