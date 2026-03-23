@if ($paginator->hasPages())
<div class="pagination">
    @if ($paginator->onFirstPage())
        <span><i class="fas fa-chevron-left"></i> Anterior</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-chevron-left"></i> Anterior</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">Siguiente <i class="fas fa-chevron-right"></i></a>
    @else
        <span>Siguiente <i class="fas fa-chevron-right"></i></span>
    @endif
</div>
@endif
