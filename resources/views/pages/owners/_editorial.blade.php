@php
    $image = \App\Features\Catalog\Support\MediaImage::class;
    $groups = collect($owner['categories'])->map(function (array $category) use ($channels): array {
        [$label, $icon] = match ($category['label']) {
            'Television networks', 'Television' => ['Television', 'tv'],
            'Radio' => ['Radio', 'radio'],
            'Digital publishing' => ['Digital publishing', 'digital'],
            'Out-of-home' => ['Out-of-home', 'billboard'],
            'TV home shopping' => ['TV home shopping', 'briefcase'],
            'Streaming' => ['Streaming', 'play'],
            'News and press' => ['News & press', 'print'],
            'Apps' => ['Apps', 'screen'],
            'Podcast & events' => ['Podcast & events', 'megaphone'],
            default => [$category['label'], 'layers'],
        };

        return [
            'id' => 'network-'.\Illuminate\Support\Str::slug($category['label']),
            'label' => $label,
            'icon' => $icon,
            'channels' => collect($channels)->where('category', $category['label'])->values(),
        ];
    })->filter(fn (array $group): bool => $group['channels']->isNotEmpty())->values();

    $featuredConfig = $editorial['featured'] ?? [];
    // Use the actual catalogue results; editorial selection never creates an offer.
    $offersBySlug = $rateCards->keyBy(fn ($offer): string => (string) $offer->slug);
    $featured = collect(array_keys($featuredConfig))
        ->map(fn (string $slug) => $offersBySlug->get($slug))
        ->filter()
        ->values();
    $remaining = $rateCards->reject(fn ($offer): bool => $featured->contains(fn ($selected): bool => $selected->slug === $offer->slug))->values();
    $ownerLogo = ! empty($owner['logo']) ? 'assets/partners/'.$owner['logo'] : null;
@endphp

<div class="owner-editorial">
    <section class="owner-editorial-hero" aria-labelledby="owner-editorial-title">
        <div class="container owner-editorial-hero__inner">
            <nav class="breadcrumb owner-editorial-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
                <a href="{{ route('media.index') }}">Media</a><span aria-hidden="true">/</span>
                <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
                <span aria-current="page">{{ $owner['name'] }}</span>
            </nav>
            <div class="owner-editorial-hero__identity">
                @if ($ownerLogo)
                    <img src="{{ $image::url($ownerLogo) }}" alt="{{ $owner['name'] }}" decoding="async">
                @else
                    <span>{{ $owner['mark'] ?? $owner['name'] }}</span>
                @endif
            </div>
            <div class="owner-editorial-hero__copy">
                <span class="owner-editorial-kicker">Media owner</span>
                <h1 id="owner-editorial-title">{{ $owner['name'] }}</h1>
                <p class="owner-editorial-tagline">{{ $editorial['tagline'] }}</p>
                <p class="owner-editorial-intro">{{ $editorial['intro'] }}</p>
                <div class="owner-editorial-actions">
                    <a class="button button--primary" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                    <a class="button button--secondary" href="{{ route('contact') }}"><x-dm-icon name="mail" :size="18"/> Talk to a specialist</a>
                </div>
            </div>
            <div class="owner-editorial-hero__visual">
                <img src="{{ $image::url($editorial['hero']) }}" alt="{{ $editorial['hero_alt'] }}" fetchpriority="high" decoding="async">
            </div>
        </div>
    </section>

    <nav class="owner-editorial-nav" aria-label="Explore media categories">
        <div class="container owner-editorial-nav__inner">
            @foreach ($groups as $group)
                <a href="#{{ $group['id'] }}"><x-dm-icon :name="$group['icon']" :size="21"/><span>{{ $group['label'] }}</span></a>
            @endforeach
        </div>
    </nav>

    <section class="container owner-editorial-opportunities" id="rate-cards" aria-labelledby="owner-opportunities-title">
        <div class="owner-editorial-heading">
            <div><span class="owner-editorial-kicker">Rate cards &amp; packages</span><h2 id="owner-opportunities-title">Explore advertising opportunities</h2></div>
            <p>Explore available rate-card references across the group. Rates, validity and availability are subject to confirmation.</p>
        </div>
        @if ($featured->isNotEmpty())
            <div class="owner-editorial-offer-grid">
                @foreach ($featured as $offer)
                    @include('pages.owners._editorial-offer', ['offer' => $offer, 'feature' => $featuredConfig[$offer->slug]])
                @endforeach
            </div>
        @endif

        @if ($remaining->isNotEmpty())
            <div class="owner-editorial-more">
                @if ($featured->isNotEmpty())<h3>More opportunities</h3>@endif
                <div class="owner-editorial-more__grid">
                    @foreach ($remaining as $offer)
                        @include('pages.owners._editorial-offer', ['offer' => $offer, 'feature' => []])
                    @endforeach
                </div>
            </div>
        @endif

        @if ($rateCards->isEmpty())
            <div class="owner-editorial-empty">
                <x-dm-icon name="document" :size="34"/>
                <div><h3>Request a media brief</h3><p>Our team can help you confirm current formats, rates and availability.</p></div>
                <a class="button button--secondary" href="{{ route('contact') }}">Talk to a specialist</a>
            </div>
        @endif
        <div class="owner-editorial-catalogue-links">
            <a class="text-link" href="{{ route('media.index', ['owner' => $owner['slug']]) }}">Browse all {{ $owner['name'] }} media <span aria-hidden="true">→</span></a>
            <a class="text-link" href="{{ route('rates.index', ['owner' => $owner['slug']]) }}">Compare rate lines <span aria-hidden="true">→</span></a>
        </div>
    </section>

    <section class="container owner-editorial-network" aria-labelledby="owner-network-title">
        <div class="owner-editorial-heading">
            <div><span class="owner-editorial-kicker">The network</span><h2 id="owner-network-title">Explore the Media Prima network</h2></div>
            <p class="owner-editorial-count">{{ $groups->sum(fn (array $group): int => $group['channels']->count()) }} brands &amp; channels <span aria-hidden="true">·</span> {{ $groups->count() }} media categories</p>
        </div>
        <div class="owner-editorial-roster">
            @foreach ($groups as $group)
                <section class="owner-editorial-group" id="{{ $group['id'] }}" aria-labelledby="{{ $group['id'] }}-title">
                    <div class="owner-editorial-group__heading">
                        <x-dm-icon :name="$group['icon']" :size="27"/>
                        <div><h3 id="{{ $group['id'] }}-title">{{ $group['label'] }}</h3><span>{{ $group['channels']->count() }} {{ $group['channels']->count() === 1 ? 'entry' : 'entries' }}</span></div>
                    </div>
                    <div class="owner-editorial-brands">
                        @foreach ($group['channels'] as $channel)
                            <a class="owner-editorial-brand" href="{{ route('media.channel', [$owner['slug'], $channel['slug']]) }}" aria-label="Explore {{ $channel['name'] }}">
                                @if (! empty($channel['logo']) && is_file(public_path('assets/partners/'.$channel['logo'])))
                                    <img src="{{ $image::url('assets/partners/'.$channel['logo']) }}" alt="" loading="lazy" decoding="async">
                                @endif
                                <span>{{ $channel['name'] }}</span>
                                <x-dm-icon name="arrow" :size="13"/>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>

    <section class="container owner-editorial-closing" aria-labelledby="owner-closing-title">
        <div class="owner-editorial-closing__panel">
            <div class="owner-editorial-closing__visual">
                <img src="{{ $image::url($editorial['closing_image']) }}" alt="{{ $editorial['closing_alt'] }}" loading="lazy" decoding="async">
            </div>
            <div class="owner-editorial-closing__copy">
                <span class="owner-editorial-kicker">Plan across the group</span>
                <h2 id="owner-closing-title">Build a cross-channel proposal</h2>
                <p>Combine television, radio, digital, outdoor and commerce opportunities into a campaign brief tailored to your goals.</p>
                <div class="owner-editorial-actions">
                    <a class="button button--primary" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                    <a class="button button--secondary" href="{{ route('contact') }}">Talk to our team</a>
                </div>
            </div>
        </div>
        <a class="owner-editorial-back text-link" href="{{ route('media.owners') }}">← Explore all media owners</a>
    </section>
</div>
