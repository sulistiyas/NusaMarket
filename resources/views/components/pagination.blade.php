@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="dt-pagination-nav">
        <div class="dt-pagination-info">
            <span>
                {!! __('Menampilkan') !!}
                <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
                {!! __('hingga') !!}
                <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
                {!! __('dari') !!}
                <strong>{{ $paginator->total() }}</strong>
                {!! __('hasil') !!}
            </span>
        </div>

        <ul class="dt-pagination-links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="page-link" aria-hidden="true">
                        <i class="fas fa-chevron-left"></i> Prev
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <i class="fas fa-chevron-left"></i> Prev
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="page-link" aria-hidden="true">
                        Next <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
