@if ($paginator->hasPages())
    <div class="pagination d-flex justify-content-center mt-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a href="#" class="rounded" style="pointer-events: none; opacity: 0.5;">&laquo;</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="rounded">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a href="#" class="rounded" style="pointer-events: none; opacity: 0.5;">...</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="#" class="rounded active">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="rounded">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="rounded">&raquo;</a>
        @else
            <a href="#" class="rounded" style="pointer-events: none; opacity: 0.5;">&raquo;</a>
        @endif
    </div>
@endif

<style>
.pagination a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 3px;
    border: 1px solid #ddd;
    background-color: white;
    color: #0d6efd;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.pagination a.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
    font-weight: bold;
}
</style>