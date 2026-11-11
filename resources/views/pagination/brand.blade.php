@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="pager__link is-disabled" aria-disabled="true">← Previous</span>
        @else
            <a class="pager__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" @if (! empty($livewire)) wire:click="previousPage('page')" @endif>← Previous</a>
        @endif

        <span class="pager__pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pager__link is-disabled">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pager__link is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pager__link" href="{{ $url }}" @if (! empty($livewire)) wire:click="gotoPage({{ $page }}, 'page')" @endif aria-label="Go to page {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </span>

        @if ($paginator->hasMorePages())
            <a class="pager__link" href="{{ $paginator->nextPageUrl() }}" rel="next" @if (! empty($livewire)) wire:click="nextPage('page')" @endif>Next →</a>
        @else
            <span class="pager__link is-disabled" aria-disabled="true">Next →</span>
        @endif
    </nav>
@endif
