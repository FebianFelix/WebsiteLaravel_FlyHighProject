@if($paginator->hasPages())
<nav class="pager" aria-label="Navigasi halaman">
    @if($paginator->onFirstPage())
        <span class="btn btn-ghost btn-small disabled">← Sebelumnya</span>
    @else
        <a class="btn btn-outline btn-small" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Sebelumnya</a>
    @endif
    <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    @if($paginator->hasMorePages())
        <a class="btn btn-outline btn-small" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya →</a>
    @else
        <span class="btn btn-ghost btn-small disabled">Berikutnya →</span>
    @endif
</nav>
@endif
