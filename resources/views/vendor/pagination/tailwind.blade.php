@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between flex-wrap">
        {{-- "Showing X to Y of Z results" text --}}
        <div class="mb-4 sm:mb-0">
            <p class="text-sm text-gray-700 dark:text-gray-400">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        {{-- Pagination links --}}
        <div class="flex-1 flex items-center justify-center sm:justify-end">
            <div class="inline-flex shadow-sm rounded-md space-x-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-600">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md leading-5 hover:bg-gray-50 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:active:bg-gray-700">
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-black bg-blue-600 border border-blue-600 rounded-md leading-5 focus:outline-none focus:ring ring-blue-300 transition-all duration-150">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:active:bg-gray-700" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 ml-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:active:bg-gray-700" aria-label="{{ __('pagination.next') }}">
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="relative inline-flex items-center px-3 py-2 ml-1 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed leading-5 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-600">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
