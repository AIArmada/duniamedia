<div class="plans-workspace">
    <section class="plans-hero" aria-labelledby="plans-title">
        <div class="container plans-hero__inner">
            <div class="plans-hero__copy">
                <span class="eyebrow">Campaign workspace</span>
                <h1 id="plans-title">Your campaigns.<em>In one place.</em></h1>
                <p>Plan your media mix, manage your investment and turn ideas into proposals.</p>
                <div class="plans-actions">
                    <a class="button button--primary" href="#new-campaign-plan"><span aria-hidden="true">+</span> New campaign plan</a>
                    <a class="button button--secondary" href="{{ route('advisor') }}"><x-dm-icon name="compass" :size="19"/> Use media advisor</a>
                </div>
            </div>
            <picture class="plans-hero__image">
                <img src="{{ asset('assets/img/v2/service-planning-960.webp') }}" srcset="{{ asset('assets/img/v2/service-planning-480.webp') }} 480w, {{ asset('assets/img/v2/service-planning-640.webp') }} 640w, {{ asset('assets/img/v2/service-planning-960.webp') }} 960w, {{ asset('assets/img/v2/service-planning-1280.webp') }} 1280w" sizes="(max-width: 760px) 92vw, 60vw" alt="" fetchpriority="high" decoding="async">
            </picture>
        </div>
    </section>

    <div class="container plans-overview">
        <dl class="plans-metrics" aria-label="Saved campaign overview">
            <div class="plans-metric"><x-dm-icon name="briefcase" :size="30"/><div><dt>Saved plans</dt><dd>{{ number_format($overview['count']) }}</dd></div></div>
            <div class="plans-metric"><x-dm-icon name="layers" :size="30"/><div><dt>Shortlisted media</dt><dd>{{ number_format($overview['items']) }}</dd></div></div>
            <div class="plans-metric"><x-dm-icon name="chart" :size="30"/><div><dt>Estimated investment</dt><dd>RM {{ number_format($overview['total'] / 100, 2) }}</dd></div></div>
            <div class="plans-metric"><x-dm-icon name="target" :size="30"/><div><dt>Package savings</dt><dd>RM {{ number_format($overview['discount'] / 100, 2) }}</dd></div></div>
        </dl>
        <p class="plans-note">Totals cover all your saved working plans, including alternative versions. Estimates and savings are subject to confirmation; no media is reserved until confirmed.</p>
    </div>

    <section class="container plans-layout" aria-label="Manage campaign plans">
        <section class="plans-list" aria-labelledby="saved-plans-title">
            <h2 id="saved-plans-title">Your campaign plans</h2>
            <p class="plans-intro">Manage your saved plans, add media, apply package savings and request a proposal.</p>
            <div class="plans-toolbar" role="search" aria-label="Search saved plans">
                <flux:input type="search" wire:model.live.debounce.350ms="search" placeholder="Search campaign plans…" aria-label="Search campaign plans" icon="magnifying-glass" />
                <flux:select wire:model.live="sort" aria-label="Sort campaign plans">
                    <option value="recent">Recently updated</option>
                    <option value="name">Name A–Z</option>
                    <option value="investment">Highest estimated total</option>
                </flux:select>
            </div>
            <div class="plans-list__status">
                <span aria-live="polite">{{ $plans->count() }} {{ $plans->count() === 1 ? 'plan' : 'plans' }} @if ($search !== '') found @endif</span>
                @if ($search !== '')
                    <button type="button" class="plans-clear" wire:click="$set('search', '')">Clear search</button>
                @endif
            </div>

            <div class="plans-cards" wire:loading.class="is-loading" wire:target="search,sort,delete">
                @forelse ($plans as $entry)
                    @php
                        $plan = $entry['plan'];
                        $summary = $entry['summary'];
                        $planName = $plan->getMetadataValue('name') ?: 'Untitled plan';
                        $planImage = $summary['items']->first()?->attributes->get('image');
                        $start = $plan->getMetadataValue('starts_on');
                        $end = $plan->getMetadataValue('ends_on');
                    @endphp
                    <article class="plan-tile" wire:key="saved-plan-{{ $plan->instance }}">
                        <div class="plan-tile__image">
                            @if ($planImage)
                                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($planImage) }}" alt="" loading="lazy" decoding="async">
                            @else
                                <x-dm-icon name="briefcase" :size="38"/>
                            @endif
                        </div>
                        <div class="plan-tile__content">
                            <div class="plan-tile__heading">
                                <h3><a href="{{ route('plans.show', $plan->instance) }}">{{ $planName }}</a></h3>
                                <span class="plan-tile__status">Working plan</span>
                            </div>
                            @if ($plan->getMetadataValue('objective'))
                                <p class="plan-tile__objective">{{ $plan->getMetadataValue('objective') }}</p>
                            @endif
                            <p class="plan-tile__brief">{{ \Illuminate\Support\Str::limit((string) ($plan->getMetadataValue('brief') ?: 'No brief yet.'), 160) }}</p>
                            <div class="plan-tile__meta">
                                <span><x-dm-icon name="calendar" :size="16"/> @if ($start && $end) {{ $start }} – {{ $end }} @elseif ($start) From {{ $start }} @elseif ($end) Until {{ $end }} @else Dates not set @endif</span>
                                <span><x-dm-icon name="layers" :size="16"/> {{ $summary['items']->count() }} media {{ $summary['items']->count() === 1 ? 'item' : 'items' }}</span>
                            </div>
                            <dl class="plan-tile__financials">
                                <div><dt>Subtotal</dt><dd>RM {{ number_format($summary['subtotal'] / 100, 2) }}</dd></div>
                                <div><dt>Package savings</dt><dd>RM {{ number_format($summary['discount'] / 100, 2) }}</dd></div>
                                <div class="plan-tile__total"><dt>Estimated total</dt><dd>RM {{ number_format($summary['total'] / 100, 2) }}</dd></div>
                            </dl>
                        </div>
                        <div class="plan-tile__actions">
                            <flux:button variant="primary" href="{{ route('plans.show', $plan->instance) }}" icon:trailing="arrow-up-right">Open plan</flux:button>
                            <flux:button variant="ghost" type="button" wire:click="delete('{{ $plan->instance }}')" wire:confirm="Delete this plan?">Delete</flux:button>
                        </div>
                    </article>
                @empty
                    <div class="plans-empty">
                        <div class="plans-empty__icon"><x-dm-icon name="briefcase" :size="38"/></div>
                        @if ($search !== '')
                            <h3>No plans match your search.</h3>
                            <p>Try another campaign name, objective or keyword.</p>
                            <flux:button type="button" variant="outline" wire:click="$set('search', '')">Clear search</flux:button>
                        @else
                            <h3>No campaign plans yet.</h3>
                            <p>Start a plan and add media as you shortlist, or use the advisor to explore a campaign direction.</p>
                            <div class="plans-actions">
                                <a class="button button--primary" href="#new-campaign-plan">Create your first plan</a>
                                <a class="button button--secondary" href="{{ route('media.index') }}">Explore media</a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="plans-create" id="new-campaign-plan" aria-labelledby="new-plan-title">
            <h2 id="new-plan-title">Create a new campaign plan</h2>
            <p class="plans-intro">Start a working version. You can update it at any time.</p>
            <form wire:submit="create" class="plans-form">
                <flux:input wire:model="name" required placeholder="e.g. Merdeka campaign" label="Plan name" />
                <flux:input wire:model="objective" placeholder="Awareness, launch, leads…" label="Objective" description:trailing="Optional" />
                <div class="plans-form__dates">
                    <flux:input wire:model="starts_on" type="date" label="Start date" description:trailing="Optional" />
                    <flux:input wire:model="ends_on" type="date" label="End date" description:trailing="Optional" />
                </div>
                <flux:textarea wire:model="brief" rows="4" placeholder="Audience, key message, timing and requirements…" label="Brief" description:trailing="Optional" />
                <flux:button type="submit" variant="primary" class="w-full" icon:trailing="arrow-up-right">Create plan</flux:button>
            </form>
        </aside>
    </section>

    <section class="container plans-support" aria-label="Campaign planning resources">
        <article class="plans-support__card">
            <img src="{{ asset('assets/img/v2/hero-media-640.webp') }}" srcset="{{ asset('assets/img/v2/hero-media-480.webp') }} 480w, {{ asset('assets/img/v2/hero-media-640.webp') }} 640w" sizes="(max-width: 760px) 92vw, 25vw" alt="" loading="lazy" decoding="async">
            <div>
                <h2>Browse media opportunities</h2>
                <p>Discover TV, radio, outdoor, digital, print and more.</p>
                <a class="button button--secondary" href="{{ route('media.index') }}">Explore media <span aria-hidden="true">→</span></a>
            </div>
        </article>
        <article class="plans-support__card">
            <img src="{{ asset('assets/img/v2/persona-brand-640.webp') }}" srcset="{{ asset('assets/img/v2/persona-brand-480.webp') }} 480w, {{ asset('assets/img/v2/persona-brand-640.webp') }} 640w" sizes="(max-width: 760px) 92vw, 25vw" alt="" loading="lazy" decoding="async">
            <div>
                <h2>Get help from the media advisor</h2>
                <p>Find a starting direction for your budget, audience and campaign objectives.</p>
                <a class="button button--secondary" href="{{ route('advisor') }}">Use media advisor <span aria-hidden="true">→</span></a>
            </div>
        </article>
    </section>
</div>
