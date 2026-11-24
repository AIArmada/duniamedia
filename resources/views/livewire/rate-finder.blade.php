<div>
    <x-page-hero
        eyebrow="Rate finder"
        title="Search every <span class='editorial'>rate line</span>."
        lede="Filter thousands of extracted rate lines by owner, channel, medium and budget. Rates are indicative and confirmed with the media owner before booking."
    />

    <section class="section">
        <div class="container">
            <form class="rate-filters" wire:submit.prevent>
                <flux:input wire:model.live.debounce.300ms="search" label="Search rates" placeholder="Buletin Utama, 30s spot, full page…" field:class="rate-filters__search"/>
                <flux:select wire:model.live="owner" label="Media owner">
                    <option value="all">All owners</option>
                    @foreach ($owners as $option)
                        <option value="{{ $option['slug'] }}">{{ $option['name'] }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="channel" label="Channel">
                    <option value="all">All channels</option>
                    @foreach ($channels as $option)
                        <option value="{{ $option->slug }}">{{ $option->name }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="medium" label="Medium">
                    <option value="all">All media</option>
                    @foreach ($mediums as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </flux:select>
                <flux:input wire:model.live.debounce.300ms="minPrice" type="number" min="0" label="Min RM" placeholder="0"/>
                <flux:input wire:model.live.debounce.300ms="maxPrice" type="number" min="0" label="Max RM" placeholder="Any"/>
                <flux:select wire:model.live="sort" label="Sort">
                    <option value="relevance">Price: low to high</option>
                    <option value="price_desc">Price: high to low</option>
                    <option value="item">Item A–Z</option>
                </flux:select>
                <flux:button type="button" variant="outline" wire:click="clearFilters">Clear</flux:button>
            </form>

            <div class="rate-finder__summary">
                <span>{{ number_format($rates->total()) }} rate {{ $rates->total() === 1 ? 'line' : 'lines' }}</span>
                <span class="tiny muted">Indicative only · excludes SST unless stated</span>
            </div>

            <div class="rate-list rate-finder__list">
                @forelse ($rates as $rate)
                    <article class="rate-line" wire:key="rate-{{ $rate->id }}">
                        <div class="rate-line__main">
                            <div class="rate-line__title">
                                <strong>{{ $rate->item }}</strong>
                                @if ($rate->is_package)
                                    <span class="rate-line__badge">Package</span>
                                @endif
                            </div>
                            <span class="rate-line__meta">
                                {{ $rate->rateSection?->title }}
                                @if ($rate->unit)
                                    · {{ $rate->unit }}
                                @endif
                                @if ($rate->rateSection?->channels->isNotEmpty())
                                    · {{ $rate->rateSection->channels->pluck('name')->join(', ') }}
                                @endif
                            </span>
                            @if ($rate->notes)
                                <small class="rate-line__notes">{{ $rate->notes }}</small>
                            @endif
                        </div>
                        <div class="rate-line__price">
                            @if ($rate->price_rm !== null)
                                <strong>RM {{ number_format((float) $rate->price_rm, fmod((float) $rate->price_rm, 1.0) === 0.0 ? 0 : 2) }}</strong>
                                @if ($rate->price_incl_sst_rm !== null)
                                    <small>RM {{ number_format((float) $rate->price_incl_sst_rm, fmod((float) $rate->price_incl_sst_rm, 1.0) === 0.0 ? 0 : 2) }} incl. SST</small>
                                @endif
                            @else
                                <strong>On request</strong>
                            @endif
                            @if ($rate->rateSection?->product)
                                <small><a href="{{ route('offers.show', $rate->rateSection->product->slug) }}">View {{ $rate->rateSection->product->name }} ↗</a></small>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="empty-state" style="text-align:left">
                        <h3>No rates match those filters.</h3>
                        <p style="margin-left:0">Try a broader search, a different channel, or clear the filters.</p>
                    </div>
                @endforelse
            </div>

            <div class="rate-finder__pagination">
                {{ $rates->links() }}
            </div>
        </div>
    </section>
</div>
