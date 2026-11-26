<div class="creator-network">
    @php
        $featuredNiches = [
            'Food' => ['spark', 'Recipes, reviews and food stories', 'food'],
            'Lifestyle' => ['target', 'Everyday life, wellness and inspiration', 'lifestyle'],
            'Travel' => ['globe', 'Destinations, culture and local experiences', 'travel'],
            'Technology' => ['digital', 'Gadgets, apps and digital life', 'technology'],
            'Family' => ['users', 'Parenting, kids and family activities', 'family'],
            'Fashion' => ['spark', 'Style, modest wear and local brands', 'fashion'],
            'Business' => ['briefcase', 'Entrepreneurship, work and growth', 'business'],
            'Education' => ['document', 'Learning, skills and development', 'education'],
        ];
        $nicheOptions = collect(array_keys($featuredNiches))->merge($niches)->unique()->sort()->values();
        $hasFilters = $search !== '' || $niche !== 'all' || $socialPlatform !== 'all';
    @endphp

    <section class="network-hero" aria-labelledby="network-title">
        <div class="container network-hero__inner">
            <div class="network-hero__copy">
                <span class="eyebrow">Creator network</span>
                <h1 id="network-title">Real voices.<em>Real connections.</em></h1>
                <p>A curated network of Malaysian creators who bring your brand to the right audience with ideas, stories and meaningful results.</p>
                <div class="network-actions">
                    <a class="button button--primary" href="#creator-directory">Find a creator <span aria-hidden="true">→</span></a>
                    <a class="button button--secondary" href="{{ route('creators.join') }}">Join the network</a>
                </div>
            </div>
            <picture class="network-hero__image">
                <source media="(max-width: 760px)" srcset="{{ asset('assets/img/v2/hero-creators-mobile-480.webp') }} 480w, {{ asset('assets/img/v2/hero-creators-mobile-640.webp') }} 640w, {{ asset('assets/img/v2/hero-creators-mobile-960.webp') }} 960w" sizes="100vw">
                <img src="{{ asset('assets/img/v2/hero-creators-960.webp') }}" srcset="{{ asset('assets/img/v2/hero-creators-640.webp') }} 640w, {{ asset('assets/img/v2/hero-creators-960.webp') }} 960w, {{ asset('assets/img/v2/hero-creators-1280.webp') }} 1280w" sizes="(max-width: 760px) 100vw, 60vw" alt="" fetchpriority="high" decoding="async">
            </picture>
        </div>
    </section>

    <div class="container network-discovery">
        <div class="network-search" role="search" aria-label="Find creators">
            <div class="network-search__query">
                <flux:input type="search" wire:model.live.debounce.350ms="search" placeholder="Search creator name or keyword" aria-label="Search creators" icon="magnifying-glass" />
            </div>
            <div>
                <flux:select wire:model.live="niche" aria-label="Niche">
                    <option value="all">All niches</option>
                    @foreach ($nicheOptions as $nicheOption)
                        <option value="{{ $nicheOption }}" @selected($niche === $nicheOption)>{{ $nicheOption }}</option>
                    @endforeach
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="socialPlatform" aria-label="Social platform">
                    <option value="all">All platforms</option>
                    @foreach ($socialPlatforms as $platformOption)
                        <option value="{{ $platformOption }}" @selected($socialPlatform === $platformOption)>{{ $platformOption }}</option>
                    @endforeach
                </flux:select>
            </div>
            <a class="button button--primary" href="#creator-directory">Search <span aria-hidden="true">→</span></a>
        </div>
        <section class="network-section" aria-labelledby="niches-title">
            <div class="network-section__heading">
                <h2 id="niches-title">Explore by niche</h2>
                <p>Discover creators across different interests, industries and audiences.</p>
            </div>
            <div class="network-niches" role="group" aria-label="Explore by niche">
                @foreach ($featuredNiches as $label => [$nicheIcon, $description, $tone])
                    <button type="button" wire:key="niche-{{ $tone }}" class="network-niche network-niche--{{ $tone }} @if ($niche === $label) is-active @endif" wire:click="$set('niche', '{{ $label }}')" aria-pressed="{{ $niche === $label ? 'true' : 'false' }}">
                        <x-dm-icon :name="$nicheIcon" :size="28"/>
                        <strong>{{ $label }}</strong>
                        <span>{{ $description }}</span>
                    </button>
                @endforeach
            </div>
        </section>
    </div>

    <section class="container network-section network-directory" id="creator-directory" aria-labelledby="directory-title">
        <div class="network-section__heading">
            <h2 id="directory-title">Creator directory</h2>
            <p>Discover verified creators for brand collaborations, campaigns and media projects.</p>
        </div>
        <div class="network-directory__status">
            <p aria-live="polite">{{ $creators->total() }} {{ $creators->total() === 1 ? 'creator' : 'creators' }} @if ($hasFilters) found @else in the directory @endif</p>
            @if ($hasFilters)
                <button type="button" class="network-clear" wire:click="clearFilters">Clear filters <span aria-hidden="true">×</span></button>
            @endif
        </div>

        @if ($creators->isEmpty())
            <div class="network-directory__empty">
                <div class="network-directory__symbol"><x-dm-icon name="users" :size="54"/></div>
                <div>
                    @if ($hasFilters)
                        <h3>No creators match your search.</h3>
                        <p>Try another niche, platform or keyword. You can also ask our team to help find the right talent.</p>
                        <div class="network-actions">
                            <flux:button type="button" variant="primary" wire:click="clearFilters">Clear filters</flux:button>
                            <a class="button button--secondary" href="{{ route('contact') }}">Talk to our team</a>
                        </div>
                    @else
                        <h3>Our creator network is growing.</h3>
                        <p>We publish profiles after onboarding and verification. Tell us the audience or talent you need and we’ll help you explore suitable options.</p>
                        <div class="network-actions">
                            <a class="button button--primary" href="{{ route('contact') }}">Find talent for your campaign</a>
                            <a class="button button--secondary" href="{{ route('creators.join') }}">Join as a creator</a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="network-profiles" wire:loading.class="is-loading" wire:target="search,niche,socialPlatform,clearFilters">
                @foreach ($creators as $creator)
                    <article class="creator-card" wire:key="creator-{{ $creator->id }}">
                        <a class="creator-card__image" href="{{ route('creators.show', $creator->slug) }}" aria-label="View {{ $creator->displayName() }} profile">
                            @if ($creator->getFirstMediaUrl('image'))
                                <img src="{{ $creator->displayImage() }}" alt="{{ $creator->displayName() }}" loading="lazy" decoding="async">
                            @else
                                <div class="network-profile-placeholder" aria-hidden="true"><x-dm-icon name="creators" :size="52"/></div>
                            @endif
                        </a>
                        <div class="creator-card__body">
                            <div class="creator-card__meta">
                                <span class="tag tag--verified"><x-dm-icon name="check" :size="13"/>Verified</span>
                                @if ($creator->social_platform)<span class="tag">{{ $creator->social_platform }}</span>@endif
                            </div>
                            <h3>{{ $creator->displayName() }}</h3>
                            @if ($creator->primary_niche)
                                <p class="network-profile-niche">{{ $creator->primary_niche }}</p>
                            @endif
                            <p>{{ $creator->bio }}</p>
                            <a href="{{ route('creators.show', $creator->slug) }}" class="network-profile-link">View creator profile <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $creators->links('pagination.brand', ['livewire' => true]) }}</div>
        @endif
    </section>

    <section class="container network-section network-paths" aria-label="Work with Dunia Media">
        <article class="network-path">
            <picture>
                <img src="{{ asset('assets/img/v2/persona-brand-640.webp') }}" srcset="{{ asset('assets/img/v2/persona-brand-480.webp') }} 480w, {{ asset('assets/img/v2/persona-brand-640.webp') }} 640w, {{ asset('assets/img/v2/persona-brand-960.webp') }} 960w" sizes="(max-width: 760px) 100vw, 25vw" alt="" loading="lazy" decoding="async">
            </picture>
            <div class="network-path__copy">
                <span class="eyebrow">For brands</span>
                <h2>Turn creativity into brand impact.</h2>
                <p>Work with creators who fit your message, audience and campaign goals.</p>
                <a class="button button--secondary" href="{{ route('contact') }}">Plan a campaign <span aria-hidden="true">→</span></a>
            </div>
        </article>
        <article class="network-path network-path--creator">
            <picture>
                <img src="{{ asset('assets/img/v2/persona-creator-640.webp') }}" srcset="{{ asset('assets/img/v2/persona-creator-480.webp') }} 480w, {{ asset('assets/img/v2/persona-creator-640.webp') }} 640w, {{ asset('assets/img/v2/persona-creator-960.webp') }} 960w" sizes="(max-width: 760px) 100vw, 25vw" alt="" loading="lazy" decoding="async">
            </picture>
            <div class="network-path__copy">
                <span class="eyebrow">For creators</span>
                <h2>Grow, create and collaborate.</h2>
                <p>Explore brand partnerships, production support and creative development.</p>
                <a class="button button--secondary" href="{{ route('creators.join') }}">Join the network <span aria-hidden="true">→</span></a>
            </div>
        </article>
    </section>

    <section class="container network-section" aria-labelledby="network-services-title">
        <div class="network-section__heading">
            <h2 id="network-services-title">Our creator services</h2>
            <p>From finding the right talent to delivering and measuring a campaign.</p>
        </div>
        <div class="network-services">
            <article><div class="network-service-icon"><x-dm-icon name="users" :size="28"/></div><div><h3>Creator matching</h3><p>Find talent based on your brand, audience and campaign goals.</p></div></article>
            <article><div class="network-service-icon"><x-dm-icon name="camera" :size="28"/></div><div><h3>Production support</h3><p>Creative direction, scripting, filming and editing for creator-led campaigns.</p></div></article>
            <article><div class="network-service-icon"><x-dm-icon name="chart" :size="28"/></div><div><h3>Campaign measurement</h3><p>Agree on deliverables and track results against your campaign objectives.</p></div></article>
        </div>
    </section>

    <section class="container network-section network-final" aria-labelledby="network-final-title">
        <div class="network-final__panel">
            <div><h2 id="network-final-title">Ready to collaborate?</h2><p>Work with Malaysia’s creative talent or join our network.</p></div>
            <div class="network-actions">
                <a class="button button--on-dark" href="{{ route('contact') }}">Plan a campaign <span aria-hidden="true">→</span></a>
                <a class="button button--outline-light" href="{{ route('creators.join') }}">Join the network <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
</div>
