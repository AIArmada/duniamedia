<div>
    @php
        $categoryIcons = [
            'TV & Broadcast' => 'tv',
            'Radio' => 'radio',
            'Outdoor' => 'billboard',
            'Digital' => 'digital',
            'Print' => 'print',
            'Indoor' => 'spaces',
            'Creators' => 'creators',
            'Podcast' => 'radio',
        ];
    @endphp

    <x-page-hero
        eyebrow="Media opportunities"
        title="Explore the <span class='editorial'>media</span> world."
        lede="Television, radio, outdoor, digital, print, creators and custom spaces — in one place. Rates are indicative and every placement is verified with the media owner before booking."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('advisor') }}">Not sure where to start? Use the advisor</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="catalog" aria-label="Media catalogue">
        <div class="container">
            <div class="category-chips" role="group" aria-label="Filter by media category">
                <button type="button" class="chip @if ($type === 'all') is-active @endif" wire:click="$set('type', 'all')" aria-pressed="{{ $type === 'all' ? 'true' : 'false' }}"><x-dm-icon name="layers" :size="16"/>All media</button>
                @foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Digital', 'Print', 'Indoor', 'Creators', 'Podcast'] as $category)
                    <button type="button" class="chip @if ($type === $category) is-active @endif" wire:click="$set('type', '{{ $category }}')" aria-pressed="{{ $type === $category ? 'true' : 'false' }}"><x-dm-icon :name="$categoryIcons[$category] ?? 'spark'" :size="16"/>{{ $category }}</button>
                @endforeach
            </div>

            <div class="catalog__layout">
                <div class="filter-backdrop" data-filter-backdrop></div>

                <aside class="filter-rail" data-filter-rail id="media-filters" aria-label="Refine media results">
                    <div class="filter-rail__head">
                        <h2>Refine</h2>
                        <div style="display:flex;gap:.6rem;align-items:center">
                            <a href="{{ route('media.index') }}" wire:navigate>Reset</a>
                            <button type="button" class="filter-sheet__close" data-filter-close aria-label="Close filters">×</button>
                        </div>
                    </div>

                    <label class="field">
                        <span>Search</span>
                        <input type="search" wire:model.live.debounce.350ms="search" placeholder="Media, owner, channel, place…">
                    </label>

                    <label class="field">
                        <span>Media category</span>
                        <select wire:model.live="type">
                            <option value="all">All categories</option>
                            @foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Digital', 'Creators', 'Print', 'Podcast'] as $category)
                                <option value="{{ $category }}" @selected($type === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Owner / channel</span>
                        <select wire:model.live="vendor">
                            <option value="all">All owners</option>
                            @foreach ($vendors as $channel)
                                <option value="{{ $channel }}" @selected($vendor === $channel)>{{ $channel }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Location</span>
                        <select wire:model.live="location">
                            <option value="all">All locations</option>
                            <option value="Nationwide" @selected($location === 'Nationwide')>Nationwide</option>
                            <option value="Klang Valley" @selected($location === 'Klang Valley')>Klang Valley</option>
                            <option value="Sepang (KLIA T1)" @selected($location === 'Sepang (KLIA T1)')>Sepang (KLIA)</option>
                            <option value="Johor" @selected($location === 'Johor')>Johor</option>
                        </select>
                    </label>

                    <label class="field">
                        <span>Indicative budget</span>
                        <select wire:model.live="budget">
                            <option value="any">Any / discuss with team</option>
                            <option value="5000" @selected($budget === '5000')>Up to RM5,000</option>
                            <option value="10000" @selected($budget === '10000')>Up to RM10,000</option>
                            <option value="50000" @selected($budget === '50000')>Up to RM50,000</option>
                            <option value="100000" @selected($budget === '100000')>Up to RM100,000</option>
                            <option value="300000" @selected($budget === '300000')>Up to RM300,000</option>
                        </select>
                    </label>

                    <div class="filter-help">
                        <strong>Starting from the outcome works better.</strong>
                        <p>Tell us the audience, budget and timing. Our advisor turns that into an illustrative direction before you shortlist.</p>
                        <a class="button button--primary button--sm" href="{{ route('advisor') }}">Open the advisor <span aria-hidden="true">↗</span></a>
                    </div>

                    <div class="filter-sheet__apply">
                        <button type="button" class="button button--primary button--block" data-filter-close>
                            Show {{ $listings->total() }} {{ $listings->total() === 1 ? 'opportunity' : 'opportunities' }}
                        </button>
                    </div>
                </aside>

                <div>
                    <div class="results-bar">
                        <p class="results-bar__count" aria-live="polite">
                            {{ $listings->total() }} {{ $listings->total() === 1 ? 'opportunity' : 'opportunities' }}
                            <span>· subject to availability verification</span>
                        </p>
                        <div class="results-bar__controls">
                            <button type="button" class="button button--secondary button--sm filter-open" data-filter-open aria-expanded="false" aria-controls="media-filters">
                                Filters
                                @if ($type !== 'all' || $vendor !== 'all' || $location !== 'all' || $budget !== 'any' || $search !== '') (active) @endif
                            </button>
                            <label class="sr-only" for="media-sort">Sort media</label>
                            <select id="media-sort" class="sort-select" wire:model.live="sort">
                                <option value="recommended" @selected($sort === 'recommended')>Recommended</option>
                                <option value="new" @selected($sort === 'new')>Newest</option>
                                <option value="az" @selected($sort === 'az')>A to Z</option>
                            </select>
                        </div>
                    </div>

                    @if ($search !== '' || $type !== 'all' || $vendor !== 'all' || $location !== 'all' || $budget !== 'any')
                        <div class="applied-filters">
                            <span>Applied:</span>
                            @if ($search !== '')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('search', '')">“{{ $search }}” ×</button>
                            @endif
                            @if ($type !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('type', 'all')">{{ $type }} ×</button>
                            @endif
                            @if ($vendor !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('vendor', 'all')">{{ $vendor }} ×</button>
                            @endif
                            @if ($location !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('location', 'all')">{{ $location }} ×</button>
                            @endif
                            @if ($budget !== 'any')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('budget', 'any')">Up to RM{{ number_format((int) $budget) }} ×</button>
                            @endif
                            <button class="text-link" type="button" wire:click="clearFilters">Clear all</button>
                        </div>
                    @endif

                    <div class="media-grid" wire:loading.class="is-loading" wire:target="search,type,vendor,location,budget,sort">
                        @forelse ($listings as $item)
                            <x-media-card :item="$item" wire:key="media-{{ $item->id }}"/>
                        @empty
                            <div class="empty-state">
                                <h3>No media matches those filters.</h3>
                                <p>Try widening the location or budget, or tell us what you are trying to achieve and we will suggest a direction.</p>
                                <div class="empty-state__actions">
                                    <button class="button button--primary" type="button" wire:click="clearFilters">Clear filters</button>
                                    <a class="button button--secondary" href="{{ route('advisor') }}">Ask the media advisor</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="pagination-wrap">{{ $listings->links('pagination.brand', ['livewire' => true]) }}</div>
                    <p class="tiny muted" style="margin-top:1rem">Rates shown are indicative guides from available rate cards, before applicable taxes and discounts. No payment, reservation or supplier confirmation happens online.</p>
                </div>
            </div>
        </div>
    </section>
</div>
