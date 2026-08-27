@if ($paginator->hasPages())
    <nav class="prosan-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="prosan-pagination-info">
            @if (method_exists($paginator, 'firstItem') && $paginator->firstItem())
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
            @endif
        </div>

        <ul class="prosan-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span class="prosan-page-link is-disabled" aria-disabled="true">&lsaquo; Previous</span></li>
            @else
                <li><a class="prosan-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Previous</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="prosan-page-link is-disabled">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="prosan-page-link is-active" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="prosan-page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a class="prosan-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rsaquo;</a></li>
            @else
                <li><span class="prosan-page-link is-disabled" aria-disabled="true">Next &rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
