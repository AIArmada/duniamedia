<div class="media-explorer">
    @php
        $mediumIcons = [
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

    <section class="explorer-hero" aria-labelledby="explorer-title">
        <div class="container explorer-hero__inner">
            <div class="explorer-hero__copy">
                <span class="eyebrow">Media explorer</span>
                <h1 id="explorer-title">Find the right media.<br><em>Make a bigger impact.</em></h1>
                <p>Discover advertising opportunities across Malaysia. From TV and radio to outdoor, digital and creators, find the media that fits your brand, audience and goals.</p>
                <a class="explorer-text-link" href="{{ route('media.owners') }}">Browse media owners <span aria-hidden="true">↗</span></a>
            </div>
            <picture class="explorer-hero__image">
                <source media="(max-width: 760px)" srcset="{{ asset('assets/img/v2/hero-media-mobile-480.webp') }} 480w, {{ asset('assets/img/v2/hero-media-mobile-640.webp') }} 640w, {{ asset('assets/img/v2/hero-media-mobile-960.webp') }} 960w" sizes="100vw">
                <img src="{{ asset('assets/img/v2/hero-media-960.webp') }}" srcset="{{ asset('assets/img/v2/hero-media-640.webp') }} 640w, {{ asset('assets/img/v2/hero-media-960.webp') }} 960w, {{ asset('assets/img/v2/hero-media-1280.webp') }} 1280w" sizes="(max-width: 760px) 100vw, 62vw" alt="" fetchpriority="high" decoding="async">
            </picture>
        </div>
    </section>

    <div class="container explorer-discovery">
        <div class="explorer-search" role="search" aria-label="Find media opportunities">
            <div class="explorer-search__query">
                <flux:input type="search" wire:model.live.debounce.350ms="search" aria-label="Search media" placeholder="Search media, channel or location" icon="magnifying-glass" />
            </div>
            <div class="explorer-search__select">
                <flux:select wire:model.live="location" aria-label="Location">
                    <option value="all">Any location</option>
                    @foreach ($locations as $locationOption)
                        <option value="{{ $locationOption }}">{{ $locationOption }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div class="explorer-search__select">
                <flux:select wire:model.live="budget" aria-label="Indicative budget">
                    <option value="any">Any budget</option>
                    <option value="5000">Up to RM5,000</option>
                    <option value="10000">Up to RM10,000</option>
                    <option value="50000">Up to RM50,000</option>
                    <option value="100000">Up to RM100,000</option>
                    <option value="300000">Up to RM300,000</option>
                </flux:select>
            </div>
            <a class="button button--primary explorer-search__action" href="#media-results">Explore media <span aria-hidden="true">→</span></a>
        </div>

        <div class="explorer-categories__heading">
            <h2>Browse by media type</h2>
            <button type="button" class="explorer-text-link" wire:click="$set('medium', 'all')" aria-pressed="{{ $medium === 'all' ? 'true' : 'false' }}">All media <span aria-hidden="true">→</span></button>
        </div>
        <div class="explorer-categories" role="group" aria-label="Filter by medium">
            @foreach ($mediumIcons as $mediumOption => $mediumIcon)
                <button type="button" wire:key="medium-{{ $mediumIcon }}-{{ $mediumOption }}" class="explorer-category @if ($medium === $mediumOption) is-active @endif" wire:click="$set('medium', '{{ $mediumOption }}')" aria-pressed="{{ $medium === $mediumOption ? 'true' : 'false' }}">
                    <x-dm-icon :name="$mediumIcon" :size="30"/>
                    <span>{{ $mediumOption }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <section class="catalog explorer-catalog" id="media-results" aria-label="Media catalogue">
        <div class="container">
            <div class="catalog__layout">
                <div class="filter-backdrop" data-filter-backdrop></div>

                <aside class="filter-rail" data-filter-rail id="media-filters" aria-label="Refine media results">
                    <div class="filter-rail__head">
                        <h2>Filters</h2>
                        <div style="display:flex;gap:.6rem;align-items:center">
                            <button class="explorer-text-link" type="button" wire:click="clearFilters">Reset</button>
                            <button type="button" class="filter-sheet__close" data-filter-close aria-label="Close filters">×</button>
                        </div>
                    </div>

                    <flux:select wire:model.live="platform" label="Platform">
                        <option value="all">Traditional and digital</option>
                        <option value="traditional" @selected($platform === 'traditional')>Traditional media</option>
                        <option value="digital" @selected($platform === 'digital')>Digital and creators</option>
                    </flux:select>

                    <flux:select wire:model.live="format" label="Format">
                        <option value="all">All formats</option>
                        @foreach ($formats as $formatOption)
                            <option value="{{ $formatOption->format_code }}" @selected($format === $formatOption->format_code)>{{ $formatOption->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="owner" label="Media owner / seller">
                        <option value="all">All owners</option>
                        @foreach ($owners as $ownerOption)
                            <option value="{{ $ownerOption['slug'] }}" @selected($owner === $ownerOption['slug'])>{{ $ownerOption['display_name'] }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="channel" label="Channel / brand">
                        <option value="all">All channels</option>
                        @foreach ($channels as $channelOption)
                            <option value="{{ $channelOption }}" @selected($channel === $channelOption)>{{ $channelOption }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="availability" label="Availability">
                        <option value="all">Any availability</option>
                        <option value="available" @selected($availability === 'available')>Available</option>
                        <option value="limited" @selected($availability === 'limited')>Limited</option>
                        <option value="on_request" @selected($availability === 'on_request')>On request</option>
                        <option value="unavailable" @selected($availability === 'unavailable')>Unavailable</option>
                    </flux:select>

                    <div class="filter-sheet__apply">
                        <flux:button type="button" variant="primary" class="w-full" data-filter-close>
                            Show {{ $offerings->total() }} {{ $offerings->total() === 1 ? 'opportunity' : 'opportunities' }}
                        </flux:button>
                    </div>
                </aside>

                <div class="explorer-results">
                    <h2 class="explorer-results__title">Explore media opportunities</h2>
                    <div class="results-bar">
                        <p class="results-bar__count" aria-live="polite">
                            {{ $offerings->total() }} {{ $offerings->total() === 1 ? 'opportunity' : 'opportunities' }}
                            <span>· Rates and availability subject to confirmation</span>
                        </p>
                        <div class="results-bar__controls">
                            <flux:button type="button" variant="outline" size="sm" class="filter-open" data-filter-open aria-expanded="false" aria-controls="media-filters">
                                Filters
                                @if ($platform !== 'all' || $medium !== 'all' || $format !== 'all' || $owner !== 'all' || $channel !== 'all' || $location !== 'all' || $availability !== 'all' || $budget !== 'any' || $search !== '') (active) @endif
                            </flux:button>
                            <div class="sort-control">
                                <flux:select id="media-sort" wire:model.live="sort" aria-label="Sort media">
                                    <option value="recommended" @selected($sort === 'recommended')>Recommended</option>
                                    <option value="new" @selected($sort === 'new')>Newest</option>
                                    <option value="az" @selected($sort === 'az')>A to Z</option>
                                </flux:select>
                            </div>
                        </div>
                    </div>

                    @if ($search !== '' || $platform !== 'all' || $medium !== 'all' || $format !== 'all' || $owner !== 'all' || $channel !== 'all' || $location !== 'all' || $availability !== 'all' || $budget !== 'any')
                        <div class="applied-filters">
                            <span>Applied:</span>
                            @if ($search !== '')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('search', '')">“{{ $search }}” ×</button>
                            @endif
                            @if ($platform !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('platform', 'all')">{{ $platform === 'traditional' ? 'Traditional media' : 'Digital and creators' }} ×</button>
                            @endif
                            @if ($medium !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('medium', 'all')">{{ $medium }} ×</button>
                            @endif
                            @if ($format !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('format', 'all')">{{ $formats->firstWhere('format_code', $format)?->name ?? $format }} ×</button>
                            @endif
                            @if ($owner !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('owner', 'all')">{{ collect($owners)->firstWhere('slug', $owner)['display_name'] ?? $owner }} ×</button>
                            @endif
                            @if ($channel !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('channel', 'all')">{{ $channel }} ×</button>
                            @endif
                            @if ($location !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('location', 'all')">{{ $location }} ×</button>
                            @endif
                            @if ($availability !== 'all')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('availability', 'all')">{{ str_replace('_', ' ', $availability) }} ×</button>
                            @endif
                            @if ($budget !== 'any')
                                <button class="chip chip--dismiss" type="button" wire:click="$set('budget', 'any')">Up to RM{{ number_format((int) $budget) }} ×</button>
                            @endif
                            <button class="text-link" type="button" wire:click="clearFilters">Clear all</button>
                        </div>
                    @endif

                    <div class="media-grid" wire:loading.class="is-loading" wire:target="search,platform,medium,format,owner,channel,location,availability,budget,sort">
                        @forelse ($offerings as $item)
                            <x-media-card :item="$item" :explorer="true" wire:key="media-{{ $item->id }}"/>
                        @empty
                            <div class="empty-state">
                                <h3>No media matches those filters.</h3>
                                <p>Try widening the medium, owner or location, or tell us what you are trying to achieve and we will suggest a direction.</p>
                                <div class="empty-state__actions">
                                    <flux:button type="button" variant="primary" wire:click="clearFilters">Clear filters</flux:button>
                                    <flux:button href="{{ route('advisor') }}" variant="outline">Ask the media advisor</flux:button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="pagination-wrap">{{ $offerings->links('pagination.brand', ['livewire' => true]) }}</div>
                    <p class="explorer-disclaimer">Rates shown are indicative guides from available rate cards, before applicable taxes and discounts. No payment, reservation or media owner confirmation happens online.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container explorer-planning" aria-labelledby="explorer-planning-title">
        <div class="explorer-planning__panel">
            <picture>
                <img src="{{ asset('assets/img/v2/service-planning-640.webp') }}" srcset="{{ asset('assets/img/v2/service-planning-480.webp') }} 480w, {{ asset('assets/img/v2/service-planning-640.webp') }} 640w, {{ asset('assets/img/v2/service-planning-960.webp') }} 960w" sizes="(max-width: 760px) 100vw, 30vw" alt="" loading="lazy" decoding="async">
            </picture>
            <div class="explorer-planning__copy">
                <h2 id="explorer-planning-title">Not sure where to start?</h2>
                <p>Tell us your goals, budget and audience. We’ll help you find the right media mix for your campaign.</p>
                <a class="button button--on-dark" href="{{ route('advisor') }}">Plan your campaign <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
</div>
