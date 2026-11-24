<section class="work-archive" id="archive" aria-labelledby="work-archive-title">
    <div class="container">
        <div class="work-archive__heading">
            <div>
                <span class="work-kicker">Campaign archive</span>
                <h2 id="work-archive-title" class="section-title">Explore our campaign records.</h2>
                <p>Find work by client, campaign, medium or year.</p>
            </div>
            <div class="work-archive__count" aria-live="polite">
                <span>{{ $total }} {{ $total === 1 ? 'campaign' : 'campaigns' }} in view</span>
            </div>
        </div>

        <div class="work-filters">
            <div class="work-filter work-filter--search">
                <span>Search</span>
                <flux:input type="search" wire:model.live.debounce.300ms="search" placeholder="Search campaigns…" aria-label="Search campaigns" icon="magnifying-glass" />
            </div>
            <div class="work-filter">
                <span>Client</span>
                <flux:select wire:model.live="client" aria-label="Client">
                    <option value="all">All clients</option>
                    @foreach ($clients as $clientOption)
                        <option value="{{ $clientOption['slug'] }}" @selected($client === $clientOption['slug'])>{{ $clientOption['display_name'] }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div class="work-filter">
                <span>Medium</span>
                <flux:select wire:model.live="medium" aria-label="Medium">
                    <option value="all">All mediums</option>
                    @foreach ($mediums as $mediumOption)
                        <option value="{{ $mediumOption }}" @selected($medium === $mediumOption)>{{ $mediumOption }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div class="work-filter">
                <span>Year</span>
                <flux:select wire:model.live="year" aria-label="Year">
                    <option value="all">All years</option>
                    @foreach ($years as $yearOption)
                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                    @endforeach
                </flux:select>
            </div>
            <details class="work-filter-more" wire:ignore.self @if ($sector !== 'all' || $service !== 'all' || $channel !== 'all') open @endif>
                <summary><x-dm-icon name="layers" :size="19"/> More filters</summary>
                <div class="work-filter-more__fields">
                    <div class="work-filter">
                        <span>Client type</span>
                        <flux:select wire:model.live="sector" aria-label="Client type">
                            <option value="all">All client types</option>
                            <option value="government">Government &amp; public</option>
                            <option value="business">Business &amp; brands</option>
                        </flux:select>
                    </div>
                    <div class="work-filter">
                        <span>Service</span>
                        <flux:select wire:model.live="service" aria-label="Service">
                            <option value="all">All services</option>
                            @foreach ($services as $serviceOption)
                                <option value="{{ $serviceOption }}">{{ $serviceOption }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="work-filter">
                        <span>Channel</span>
                        <flux:select wire:model.live="channel" aria-label="Channel">
                            <option value="all">All channels</option>
                            @foreach ($channels as $channelOption)
                                <option value="{{ $channelOption }}">{{ $channelOption }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
            </details>
            <flux:button type="button" variant="filled" size="sm" icon="arrow-path" wire:click="clearFilters">Reset</flux:button>
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
            <span>Newest first</span>
        </div>

        <div class="work-grid" wire:loading.class="is-loading">
            @forelse ($campaigns as $campaign)
                <article class="work-card" wire:key="work-card-{{ $campaign['id'] }}">
                    @if (! empty($campaign['media']))
                        <a class="work-card__cover" href="#work-media-{{ $campaign['media'][0]['key'] }}" aria-label="Play {{ $campaign['media'][0]['title'] }}">
                            <img src="{{ asset($campaign['media'][0]['poster']) }}" alt="" loading="lazy" decoding="async">
                            <span><x-dm-icon name="play" :size="34"/></span>
                        </a>
                    @endif
                    <header class="work-card__head">
                        <div class="work-card__client">
                            <span class="work-card__logo @if (empty($campaign['client_logo'])) is-typeset @endif">
                                @if (! empty($campaign['client_logo']))
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($campaign['client_logo']) }}" alt="" loading="lazy" decoding="async">
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
                            <div><dt>Medium</dt><dd>{{ $campaign['medium'] }}</dd></div>
                            <div><dt>Channel</dt><dd>{{ $campaign['channel'] }}</dd></div>
                            <div><dt>Date</dt><dd>{{ $campaign['date'] }}</dd></div>
                        </dl>
                        @if (! empty($campaign['media']))
                            <div class="work-card__media-links">
                                @foreach ($campaign['media'] as $item)
                                    <a class="work-card__link" href="#work-media-{{ $item['key'] }}">{{ $item['type'] === 'audio' ? 'Listen' : 'Watch' }} · {{ $item['title'] }} <span aria-hidden="true">↗</span></a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="work-empty">
                    <div class="empty-state__icon"><x-dm-icon name="search" :size="24"/></div>
                    <span class="eyebrow">No matches</span>
                    <h3>No campaigns match that search.</h3>
                    <p>Try a different client, medium or channel — or reset the filters to see the full archive.</p>
                    <flux:button type="button" variant="primary" wire:click="clearFilters">Reset filters</flux:button>
                </div>
            @endforelse
        </div>

        @if ($hasMore)
            <div class="work-load">
                <flux:button type="button" variant="primary" icon:trailing="arrow-down" wire:click="loadMore">
                    Load more campaigns
                </flux:button>
            </div>
        @endif
    </div>
</section>
