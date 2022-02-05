@if ($paginator->hasPages())
    <nav role="navigation" class="flex items-center justify-between">
        <!-- Mobile view -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-200 bg-white border border-gray-100 cursor-default leading-5 rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border leading-5 rounded-md hover:bg-gray-50">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border leading-5 rounded-md hover:bg-gray-50">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-200 bg-white border border-gray-100 cursor-default leading-5 rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
            <div>
                <span class="inline-flex rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border rounded-l-md leading-5 hover:bg-gray-50">
                            <x-ui.icon name="chevron-left" />
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span>
                                <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="inline-flex items-center px-1 py-1 -ml-px text-sm font-medium text-gray-500 bg-white border cursor-default leading-5">
                                        <span class="block p-1 px-3 bg-pf-midorange text-white ">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border leading-5 hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border rounded-r-md leading-5 hover:bg-gray-50">
                            <x-ui.icon name="chevron-right" />
                        </a>
                    @else
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
