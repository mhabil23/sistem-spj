@if ($paginator->hasPages())
    <style>
        .admin-pagination-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 20px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 14px 14px;
            font-family: inherit;
        }

        .admin-pagination-info {
            font-size: 13px;
            color: #64748b;
        }

        .admin-pagination-info span {
            font-weight: 700;
            color: #0c2556;
        }

        .admin-pagination-links {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .page-link-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 5px;
            cursor: pointer;
            user-select: none;
        }

        .page-link-item:hover:not(.disabled):not(.active) {
            background: #e0f2fe;
            border-color: #bae6fd;
            color: #0284c7;
        }

        .page-link-item.active {
            background: #0284c7;
            border-color: #0284c7;
            color: #ffffff;
            font-weight: 700;
            cursor: default;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        }

        .page-link-item.disabled {
            color: #94a3b8;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .page-link-item.dots {
            border: none;
            background: transparent;
            cursor: default;
            padding: 0 4px;
            min-width: auto;
        }

        .admin-pagination-nav svg {
            display: none !important;
        }
    </style>
    <nav role="navigation" aria-label="Pagination Navigation" class="admin-pagination-nav">
        {{-- Info showing --}}
        <div class="admin-pagination-info">
            Menampilkan <span>{{ $paginator->firstItem() }}</span> s.d. <span>{{ $paginator->lastItem() }}</span> dari <span>{{ $paginator->total() }}</span> data
        </div>

        {{-- Page Buttons --}}
        <div class="admin-pagination-links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-link-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <i class="bi bi-chevron-left"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link-item" aria-label="@lang('pagination.previous')">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-link-item dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link-item active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link-item">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link-item" aria-label="@lang('pagination.next')">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="page-link-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    Next <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
