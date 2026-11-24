<div class="rate-exchange" x-data="{ filtersOpen: false, mobile: window.matchMedia('(max-width: 900px)').matches }" x-on:resize.window="mobile = window.matchMedia('(max-width: 900px)').matches; if (!mobile) filtersOpen = false" x-on:keydown.escape.window="filtersOpen = false">
    <header class="exchange-heading">
        <div class="exchange-heading__brand"><x-dm-icon name="chart" :size="34"/><span>Media Rate Exchange</span></div>
        <div class="exchange-heading__main">
            <div>
                <h1>Find the right media.<em> At the right rate.</em></h1>
                <p>Discover advertising placements, compare indicative rates and build your campaign.</p>
            </div>
            <div class="exchange-search" role="search" aria-label="Search advertising rates">
                <flux:input type="search" wire:model.live.debounce.300ms="search" icon="magnifying-glass" aria-label="Search media rates" placeholder="Channel, programme, 30s spot, full page…" />
                <div class="exchange-quick-search" aria-label="Quick searches">
                    <span>Explore:</span>
                    @foreach (['TV3', 'Hot FM', 'Billboard', 'Digital', 'Full page'] as $term)
                        <button type="button" wire:key="quick-{{ $term }}" wire:click="$set('search', '{{ $term }}')" aria-pressed="{{ $search === $term ? 'true' : 'false' }}">{{ $term }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <div class="exchange-disclosure"><x-dm-icon name="document" :size="18"/> Published rates are indicative, not confirmed quotations. SST, validity, availability and terms vary by placement.</div>

    <div class="exchange-layout">
        <div class="exchange-filter-backdrop" x-show="mobile && filtersOpen" x-cloak x-on:click="filtersOpen = false" aria-hidden="true"></div>
        <aside class="exchange-filters" :class="{ 'is-open': filtersOpen }" :role="mobile && filtersOpen ? 'dialog' : null" :aria-modal="mobile && filtersOpen ? 'true' : null" x-trap.inert.noscroll="mobile && filtersOpen" aria-label="Filter media rates" id="exchange-filters">
            <div class="exchange-filters__heading">
                <h2>Filter results</h2>
                <button class="exchange-close-filters" type="button" x-on:click="filtersOpen = false" aria-label="Close filters">×</button>
            </div>
            <flux:select wire:model.live="owner" label="Media owner">
                <option value="all">All media owners</option>
                @foreach ($owners as $option)
                    <option value="{{ $option['slug'] }}">{{ $option['name'] }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="channel" label="Channel / brand">
                <option value="all">All channels</option>
                @foreach ($channels as $option)
                    <option value="{{ $option->slug }}">{{ $option->name }}</option>
                @endforeach
            </flux:select>

            <fieldset class="exchange-mediums">
                <legend>Media type</legend>
                @foreach (collect(['all'])->merge($mediums) as $option)
                    <label class="exchange-medium" wire:key="medium-{{ $option }}"><input type="radio" wire:model.live="medium" value="{{ $option }}">{{ $option === 'all' ? 'All media' : $option }}</label>
                @endforeach
            </fieldset>

            <fieldset class="exchange-budget">
                <legend>Budget per listed unit (RM)</legend>
                <div>
                    <flux:input type="number" min="0" wire:model.live.debounce.300ms="minPrice" aria-label="Minimum price" placeholder="Min" />
                    <flux:input type="number" min="0" wire:model.live.debounce.300ms="maxPrice" aria-label="Maximum price" placeholder="Max" />
                </div>
            </fieldset>

            <flux:select wire:model.live="unit" label="Unit / duration">
                <option value="all">All units</option>
                @foreach ($units as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="package" label="Package type">
                <option value="all">All types</option>
                <option value="package">Packages</option>
                <option value="individual">Individual placements</option>
            </flux:select>
            <flux:select wire:model.live="location" label="Location">
                <option value="all">All locations</option>
                @foreach ($locations as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </flux:select>

            <flux:button type="button" variant="outline" wire:click="clearFilters">Reset filters</flux:button>
            <button type="button" class="exchange-filter-done exchange-primary" x-on:click="filtersOpen = false">Show {{ number_format($rates->total()) }} results</button>
        </aside>

        <section class="exchange-results" aria-labelledby="exchange-results-title">
            <div class="exchange-results__heading">
                <div><h2 id="exchange-results-title">{{ number_format($rates->total()) }} {{ $rates->total() === 1 ? 'rate' : 'rates' }} found</h2><p>Compare placements and check the original rate-card details.</p></div>
                <div class="exchange-results__controls">
                    <button type="button" class="exchange-filter-open" aria-controls="exchange-filters" :aria-expanded="filtersOpen" x-on:click="filtersOpen = !filtersOpen">Filters</button>
                    <flux:select wire:model.live="sort" aria-label="Sort rates">
                        <option value="price_asc">Price: low to high</option>
                        <option value="price_desc">Price: high to low</option>
                        <option value="item">Item A–Z</option>
                    </flux:select>
                </div>
            </div>

            @if ($showComparison && $compared->count() >= 2)
                <section class="exchange-comparison" aria-labelledby="exchange-compare-title">
                    <div class="exchange-comparison__heading"><div><h2 id="exchange-compare-title">Compare placements</h2><p>Rates may cover different units and deliverables. A lower price does not necessarily mean better value.</p></div><button type="button" class="exchange-secondary" wire:click="$set('showComparison', false)">Close comparison</button></div>
                    <div class="exchange-compare-scroll" tabindex="0" role="region" aria-label="Side-by-side rate comparison">
                        <table class="exchange-compare-table">
                            <thead><tr><th scope="col">Placement</th>@foreach ($compared as $rate)<th scope="col" wire:key="compare-head-{{ $rate->id }}">{{ $rate->item }}</th>@endforeach</tr></thead>
                            <tbody>
                                <tr><th scope="row">Channel</th>@foreach ($compared as $rate)<td>{{ $rate->rateSection?->channels->pluck('name')->join(', ') ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Media owner</th>@foreach ($compared as $rate)<td>{{ $rate->rateSection->channels->pluck('owner_party_id')->map(fn ($id) => $ownerNames->get($id))->filter()->unique()->join(', ') ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Location</th>@foreach ($compared as $rate)<td>{{ $rate->rateSection->product->getRelationValue('mediaProfile')?->location_label ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Rate</th>@foreach ($compared as $rate)<td><strong>{{ (float) $rate->price_rm > 0 ? 'RM '.number_format((float) $rate->price_rm, 2) : 'On request' }}</strong></td>@endforeach</tr>
                                <tr><th scope="row">Including SST</th>@foreach ($compared as $rate)<td>{{ $rate->price_incl_sst_rm !== null ? 'RM '.number_format((float) $rate->price_incl_sst_rm, 2) : 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Unit</th>@foreach ($compared as $rate)<td>{{ $rate->unit ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Price basis</th>@foreach ($compared as $rate)<td>{{ $rate->price_basis ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Type</th>@foreach ($compared as $rate)<td>{{ $rate->is_package ? 'Package' : 'Individual' }}</td>@endforeach</tr>
                                <tr><th scope="row">Validity</th>@foreach ($compared as $rate)<td>{{ $rate->validity ?: 'Confirm with owner' }}</td>@endforeach</tr>
                                <tr><th scope="row">Terms</th>@foreach ($compared as $rate)<td>{{ $rate->terms ?: 'Not stated' }}</td>@endforeach</tr>
                                <tr><th scope="row">Notes</th>@foreach ($compared as $rate)<td>{{ $rate->notes ?: '—' }}</td>@endforeach</tr>
                                <tr><th scope="row">Action</th>@foreach ($compared as $rate)<td>
                                    @if ((float) $rate->price_rm > 0)
                                        <form method="POST" action="{{ route('plans.rate.add', $rate->id) }}">@csrf<button type="submit" class="exchange-primary">+ Add to plan</button></form>
                                    @else
                                        <a class="exchange-secondary" href="{{ route('offers.show', $rate->rateSection->product->slug) }}">View details</a>
                                    @endif
                                    <button type="button" class="exchange-detail-link exchange-compare-remove" wire:click="toggleComparison('{{ $rate->id }}')">Remove from comparison</button>
                                </td>@endforeach</tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

            <div class="exchange-list" wire:loading.class="is-loading" wire:target="search,owner,channel,medium,minPrice,maxPrice,unit,package,location,sort,clearFilters">
                @forelse ($rates as $rate)
                    @php
                        $section = $rate->rateSection;
                        $product = $section->product;
                        $channelNames = $section->channels->pluck('name')->join(', ');
                        $names = $section->channels->pluck('owner_party_id')->map(fn ($id) => $ownerNames->get($id))->filter()->unique()->join(', ');
                        $locationLabel = $product->getRelationValue('mediaProfile')?->location_label;
                        $image = 'assets/ratecards/img/'.$product->slug.'.webp';
                        if (! is_file(public_path($image))) {
                            $image = $section->channels->first()?->hero_path;
                        }
                        $selectedForComparison = in_array($rate->id, $comparison, true);
                    @endphp
                    <article class="exchange-rate @if ($selectedForComparison) is-compared @endif" wire:key="rate-{{ $rate->id }}">
                        <div class="exchange-rate__image">
                            @if ($image)
                                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($image) }}" alt="" loading="lazy" decoding="async">
                            @else
                                <x-dm-icon name="document" :size="34"/>
                            @endif
                        </div>
                        <div class="exchange-rate__content">
                            <div class="exchange-rate__tags">
                                @if ($section->medium)<span>{{ $section->medium }}</span>@endif
                                @if ($rate->is_package)<span class="exchange-package">Package</span>@endif
                            </div>
                            <h3><a href="{{ route('offers.show', $product->slug) }}">{{ $rate->item }}</a></h3>
                            <p class="exchange-rate__owner">{{ $channelNames ?: $product->name }}@if ($names) <span>· {{ $names }}</span>@endif</p>
                            <p class="exchange-rate__section">{{ $section->title }}</p>
                            <div class="exchange-rate__facts">
                                @if ($rate->unit)<span><x-dm-icon name="layers" :size="15"/>{{ $rate->unit }}</span>@endif
                                @if ($locationLabel)<span><x-dm-icon name="pin" :size="15"/>{{ $locationLabel }}</span>@endif
                                @if ($rate->validity)<span><x-dm-icon name="calendar" :size="15"/>{{ $rate->validity }}</span>@endif
                            </div>
                            @if ($rate->notes || $rate->terms)
                                <details class="exchange-rate__details"><summary>Rate notes &amp; terms</summary>@if ($rate->notes)<p>{{ $rate->notes }}</p>@endif @if ($rate->terms)<p>{{ $rate->terms }}</p>@endif</details>
                            @endif
                        </div>
                        <div class="exchange-rate__price">
                            @if ((float) $rate->price_rm > 0)
                                <span class="exchange-price-label">Indicative rate</span>
                                <strong>RM {{ number_format((float) $rate->price_rm, fmod((float) $rate->price_rm, 1.0) === 0.0 ? 0 : 2) }}</strong>
                                @if ($rate->unit)<small>per listed unit: {{ $rate->unit }}</small>@endif
                                @if ($rate->price_incl_sst_rm !== null)<small>RM {{ number_format((float) $rate->price_incl_sst_rm, 2) }} incl. SST</small>@endif
                                @if ($rate->price_basis)<small>{{ $rate->price_basis }}</small>@endif
                            @else
                                <strong class="exchange-on-request">On request</strong>
                                <small>Confirm the rate with our team.</small>
                            @endif
                        </div>
                        <div class="exchange-rate__actions">
                            @if ((float) $rate->price_rm > 0)
                                <form method="POST" action="{{ route('plans.rate.add', $rate->id) }}">@csrf<button type="submit" class="exchange-primary">+ Add to plan</button></form>
                            @else
                                <a class="exchange-primary" href="{{ route('contact') }}">Enquire</a>
                            @endif
                            <button type="button" class="exchange-secondary" wire:click="toggleComparison('{{ $rate->id }}')" aria-pressed="{{ $selectedForComparison ? 'true' : 'false' }}">@if ($selectedForComparison)<x-dm-icon name="check" :size="17"/> Selected @else <x-dm-icon name="layers" :size="17"/> Compare @endif</button>
                            <a class="exchange-detail-link" href="{{ route('offers.show', $product->slug) }}">View rate card ↗</a>
                        </div>
                    </article>
                @empty
                    <div class="exchange-empty"><x-dm-icon name="search" :size="36"/><h3>No rates match those filters.</h3><p>Try a broader search or change your budget, media type or channel.</p><flux:button type="button" variant="outline" wire:click="clearFilters">Reset filters</flux:button></div>
                @endforelse
            </div>

            <div class="exchange-pagination">{{ $rates->links() }}</div>
        </section>
    </div>

    <div class="exchange-tray" aria-label="Comparison shortlist">
        <div class="exchange-tray__title"><x-dm-icon name="layers" :size="25"/><div><strong>Compare list</strong><span>{{ $compared->count() }} of 4 selected</span></div></div>
        <div class="exchange-tray__items">
            @forelse ($compared as $rate)
                <div class="exchange-tray-item" wire:key="tray-{{ $rate->id }}"><span><strong>{{ $rate->item }}</strong><small>{{ (float) $rate->price_rm > 0 ? 'RM '.number_format((float) $rate->price_rm, 2) : 'On request' }}</small></span><button type="button" wire:click="toggleComparison('{{ $rate->id }}')" aria-label="Remove {{ $rate->item }} from comparison">×</button></div>
            @empty
                <p>Select rates to compare their details.</p>
            @endforelse
        </div>
        @if ($compared->isNotEmpty())
            <div class="exchange-tray__total"><span>Known rates (one each)</span><strong>RM {{ number_format($comparisonTotal / 100, 2) }}</strong>@if ($comparisonPricedCount < $compared->count())<small>Excludes unpriced entries</small>@endif</div>
        @endif
        <div class="exchange-tray__actions">
            <button type="button" class="exchange-tray-compare" wire:click="$set('showComparison', true)" @disabled($compared->count() < 2) x-on:click="document.getElementById('exchange-results-title')?.scrollIntoView({ block: 'start' })">Compare now</button>
            <a href="{{ route('plans.index') }}" class="exchange-tray-plan">Campaign plans →</a>
            @if ($comparison !== [])<button type="button" class="exchange-tray-clear" wire:click="clearComparison">Clear comparison</button>@endif
        </div>
        @error('comparison')<p class="exchange-tray-error" role="alert">{{ $message }}</p>@enderror
    </div>
</div>
