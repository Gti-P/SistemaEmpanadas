@if ($paginator->hasPages())
<div class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span aria-disabled="true"><i class="fas fa-chevron-left"></i></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span>{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
    @else
        <span aria-disabled="true"><i class="fas fa-chevron-right"></i></span>
    @endif
</div>
@endif
