@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between py-2">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-xs font-semibold text-stone-400 bg-white border border-stone-200 cursor-not-allowed rounded-xl">
                ← Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-stone-700 bg-white border border-stone-200 rounded-xl hover:bg-stone-50 transition">
                ← Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 ml-3 text-xs font-semibold text-stone-700 bg-white border border-stone-200 rounded-xl hover:bg-stone-50 transition">
                Berikutnya →
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 ml-3 text-xs font-semibold text-stone-400 bg-white border border-stone-200 cursor-not-allowed rounded-xl">
                Berikutnya →
            </span>
        @endif
    </nav>
@endif
