@php
    $btn = 'min-w-[2.25rem] rounded-lg px-3 py-1.5 text-sm font-medium transition';
    $idle = 'bg-gray-100 text-gray-700 hover:bg-gray-200';
    $off = 'cursor-not-allowed bg-gray-100 text-gray-700 opacity-40';
@endphp
@if ($paginator->total() > 0)
    <div class="mt-4 flex flex-col items-center justify-between gap-3 border-t pt-4 sm:flex-row">
        <p class="text-sm text-gray-500">Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</p>
        @if ($paginator->hasPages())
            <nav class="flex flex-wrap items-center gap-1" aria-label="Navigasi halaman">
                @if ($paginator->onFirstPage())
                    <span class="{{ $btn }} {{ $off }}">‹ Prev</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="{{ $btn }} {{ $idle }}">‹ Prev</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="{{ $btn }} text-gray-400">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="{{ $btn }} bg-emerald-600 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $btn }} {{ $idle }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="{{ $btn }} {{ $idle }}">Next ›</a>
                @else
                    <span class="{{ $btn }} {{ $off }}">Next ›</span>
                @endif
            </nav>
        @endif
    </div>
@endif
