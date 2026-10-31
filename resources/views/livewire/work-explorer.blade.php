<section class="work-archive" id="archive" aria-labelledby="work-archive-title">
    <div class="container">
        <div class="work-archive__heading">
            <div>
                <span class="eyebrow">CAMPAIGN ARCHIVE</span>
                <h2 id="work-archive-title">Every campaign,<br><span>one place.</span></h2>
                <p>Search the record by client, campaign, platform or year. The archive is compiled from RA Media's internal client lists.</p>
            </div>
            <div class="work-archive__count" aria-live="polite">
                <strong>{{ $total }}</strong>
                <span>{{ $total === 1 ? 'campaign' : 'campaigns' }} in view</span>
            </div>
        </div>

        <div class="work-filters">
            <label class="work-filter work-filter--search">
                <span>Search</span>
                <span class="work-filter__input">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m15.5 15.5 4 4"></path></svg>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Client, campaign, platform…">
                </span>
            </label>

            <label class="work-filter">
                <span>Client type</span>
                <select wire:model.live="sector">
                    <option value="all">All clients</option>
                    <option value="government">Government &amp; public</option>
                    <option value="business">Business &amp; brands</option>
                </select>
            </label>

            <label class="work-filter">
                <span>Service</span>
                <select wire:model.live="service">
                    <option value="all">All services</option>
                    @foreach ($services as $serviceOption)
                        <option value="{{ $serviceOption }}">{{ $serviceOption }}</option>
                    @endforeach
                </select>
            </label>

            <label class="work-filter">
                <span>Channel</span>
                <select wire:model.live="channel">
                    <option value="all">All channels</option>
                    @foreach ($channels as $channelOption)
                        <option value="{{ $channelOption }}">{{ $channelOption }}</option>
                    @endforeach
                </select>
            </label>

            <label class="work-filter">
                <span>Year</span>
                <select wire:model.live="year">
                    <option value="all">All years</option>
                    @foreach ($years as $yearOption)
                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                    @endforeach
                </select>
            </label>

            <button type="button" class="work-filter__reset" wire:click="clearFilters">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4v6h6M20 20v-6h-6"></path><path d="M5.5 15a7 7 0 0 0 12.2 2M18.5 9A7 7 0 0 0 6.3 7"></path></svg>
                Reset
            </button>
        </div>

        @if ($activeClient)
            <div class="work-active-filter">
                <span>Showing work for <strong>{{ $activeClient['name'] }}</strong></span>
                <button type="button" wire:click="$set('client', 'all')">Clear client filter <span aria-hidden="true">×</span></button>
            </div>
        @endif

        <div class="work-results__meta">
            <span wire:loading.remove>Showing {{ $campaigns->count() }} of {{ $total }}</span>
            <span wire:loading>Updating the archive…</span>
            <span class="work-results__hint">Newest first</span>
        </div>

        <div class="work-grid" wire:loading.class="is-loading">
            @forelse ($campaigns as $campaign)
                <article class="work-card" wire:key="work-card-{{ $loop->index }}-{{ $campaign['client'] }}">
                    <header class="work-card__head">
                        <div class="work-card__client">
                            <span class="work-card__logo @if (empty($campaign['client_logo'])) is-typeset @endif">
                                @if (!empty($campaign['client_logo']))
                                    <img src="{{ asset($campaign['client_logo']) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span>{{ $campaign['client_short_name'] }}</span>
                                @endif
                            </span>
                            <span class="work-card__client-name">{{ $campaign['client_name'] }}</span>
                        </div>
                        <span class="work-card__year">{{ $campaign['year'] }}</span>
                    </header>

                    <div class="work-card__body">
                        <span class="work-card__service">{{ $campaign['service'] }}</span>
                        <h3>{{ $campaign['title'] }}</h3>
                        <p>{{ $campaign['description'] }}</p>

                        <dl class="work-card__facts">
                            <div>
                                <dt>Platform</dt>
                                <dd>{{ $campaign['platform'] }}</dd>
                            </div>
                            <div>
                                <dt>Date</dt>
                                <dd>{{ $campaign['date'] }}</dd>
                            </div>
                            <div>
                                <dt>Channel</dt>
                                <dd>{{ $campaign['channel'] }}</dd>
                            </div>
                        </dl>

                        @if (!empty($campaign['link']))
                            <a class="work-card__link" href="{{ $campaign['link'] }}" target="_blank" rel="noopener noreferrer">
                                View source <span aria-hidden="true">↗</span>
                            </a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="work-empty">
                    <span aria-hidden="true">✦</span>
                    <h3>No campaigns match that search.</h3>
                    <p>Try a different client, platform or year — or reset the filters to see the full archive.</p>
                    <button type="button" class="btn btn-primary" wire:click="clearFilters">Reset filters</button>
                </div>
            @endforelse
        </div>

        @if ($hasMore)
            <div class="work-load">
                <button type="button" class="btn btn-primary" wire:click="loadMore" wire:loading.attr="disabled">
                    Load more campaigns <span aria-hidden="true">↓</span>
                </button>
            </div>
        @endif
    </div>
</section>
