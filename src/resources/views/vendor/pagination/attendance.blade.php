@if ($paginator->hasPages())
<nav>
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li aria-disabled="true" aria-label="@lang('pagination.previous')">
            <button class="disabled" aria-hidden="true">&lsaquo;</button>
        </li>
        @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><button class="enabled">&lsaquo;</button></a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li aria-disabled="true"><button class="omission">{{ $element }}</button></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li aria-current="page"><button class="active">{{ $page }}</button></li>
        @else
        <li><a href="{{ $url }}"><button class="inactive">{{ $page }}</button></a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li>
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                <button class="enabled">&rsaquo;</button>
            </a>
        </li>
        @else
        <li aria-disabled="true" aria-label="@lang('pagination.next')">
            <button class="disabled" aria-hidden="true">&rsaquo;</button>
        </li>
        @endif
    </ul>
</nav>
@endif