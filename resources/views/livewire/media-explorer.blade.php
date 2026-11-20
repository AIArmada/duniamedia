<div>
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

    <x-page-hero
        eyebrow="Media opportunities"
        title="Explore the <span class='editorial'>media</span> world."
        lede="Filter by platform, medium, media owner, format and location. Every opportunity is tied to a media owner, a channel and a commercial rate card before it reaches a proposal."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('media.owners') }}">Browse media owners</a>
            <a class="button button--secondary" href="{{ route('advisor') }}">Use the media advisor</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="catalog" aria-label="Media catalogue">
        <div class="container">
            <div class="category-chips" role="group" aria-label="Filter by medium">
                <button type="button" class="chip @if ($medium === 'all') is-active @endif" wire:click="$set('medium', 'all')" aria-pressed="{{ $medium === 'all' ? 'true' : 'false' }}"><x-dm-icon name="layers" :size="16"/>All media</button>
                @foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Digital', 'Print', 'Indoor', 'Creators', 'Podcast'] as $mediumOption)
                    <button type="button" class="chip @if ($medium === $mediumOption) is-active @endif" wire:click="$set('medium', '{{ $mediumOption }}')" aria-pressed="{{ $medium === $mediumOption ? 'true' : 'false' }}"><x-dm-icon :name="$mediumIcons[$mediumOption] ?? 'spark'" :size="16"/>{{ $mediumOption }}</button>
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

                    <flux:input type="search" wire:model.live.debounce.350ms="search" label="Search" placeholder="Search media…" icon="magnifying-glass" />

                    <flux:select wire:model.live="platform" label="Platform">
                        <option value="all">Traditional and digital</option>
                        <option value="traditional" @selected($platform === 'traditional')>Traditional media</option>
                        <option value="digital" @selected($platform === 'digital')>Digital and creators</option>
                    </flux:select>

                    <flux:select wire:model.live="medium" label="Medium">
                        <option value="all">All mediums</option>
                        @foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Digital', 'Creators', 'Print', 'Podcast'] as $mediumOption)
                            <option value="{{ $mediumOption }}" @selected($medium === $mediumOption)>{{ $mediumOption }}</option>
                        @endforeach
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
                            <option value="{{ $ownerOption->slug }}" @selected($owner === $ownerOption->slug)>{{ $ownerOption->display_name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="channel" label="Channel / brand">
                        <option value="all">All channels</option>
                        @foreach ($channels as $channelOption)
                            <option value="{{ $channelOption }}" @selected($channel === $channelOption)>{{ $channelOption }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="location" label="Location">
                        <option value="all">All locations</option>
                        @foreach ($locations as $locationOption)
                            <option value="{{ $locationOption }}" @selected($location === $locationOption)>{{ $locationOption }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="availability" label="Availability">
                        <option value="all">Any availability</option>
                        <option value="available" @selected($availability === 'available')>Available</option>
                        <option value="limited" @selected($availability === 'limited')>Limited</option>
                        <option value="on_request" @selected($availability === 'on_request')>On request</option>
                        <option value="unavailable" @selected($availability === 'unavailable')>Unavailable</option>
                    </flux:select>

                    <flux:select wire:model.live="budget" label="Indicative budget">
                        <option value="any">Any / discuss with team</option>
                        <option value="5000" @selected($budget === '5000')>Up to RM5,000</option>
                        <option value="10000" @selected($budget === '10000')>Up to RM10,000</option>
                        <option value="50000" @selected($budget === '50000')>Up to RM50,000</option>
                        <option value="100000" @selected($budget === '100000')>Up to RM100,000</option>
                        <option value="300000" @selected($budget === '300000')>Up to RM300,000</option>
                    </flux:select>

                    <div class="filter-help">
                        <strong>Start with the audience, then narrow the inventory.</strong>
                        <p>The advisor turns an objective, budget and timing into an illustrative direction before you shortlist a medium or owner.</p>
                        <flux:button href="{{ route('advisor') }}" variant="primary" size="sm" icon:trailing="arrow-up-right">Open the advisor</flux:button>
                    </div>

                    <div class="filter-sheet__apply">
                        <flux:button type="button" variant="primary" class="w-full" data-filter-close>
                            Show {{ $offerings->total() }} {{ $offerings->total() === 1 ? 'opportunity' : 'opportunities' }}
                        </flux:button>
                    </div>
                </aside>

                <div>
                    <div class="results-bar">
                        <p class="results-bar__count" aria-live="polite">
                            {{ $offerings->total() }} {{ $offerings->total() === 1 ? 'opportunity' : 'opportunities' }}
                            <span>· subject to owner and availability verification</span>
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
                                <button class="chip chip--dismiss" type="button" wire:click="$set('owner', 'all')">{{ $owners->firstWhere('slug', $owner)?->display_name ?? $owner }} ×</button>
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
                            <x-media-card :item="$item" wire:key="media-{{ $item->id }}"/>
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
                    <p class="tiny muted" style="margin-top:1rem">Rates shown are indicative guides from available rate cards, before applicable taxes and discounts. No payment, reservation or media owner confirmation happens online.</p>
                </div>
            </div>
        </div>
    </section>
</div>
