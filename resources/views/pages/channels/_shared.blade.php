@php
    $image = \App\Features\Catalog\Support\MediaImage::class;
    $editorial = \App\Features\Catalog\Support\ChannelEditorial::for($channel, $heroImage ?? null);
    $logo = $channel['logo'] ?? null;
    $logoUrl = $logo
        ? (str_starts_with($logo, 'https://') || str_starts_with($logo, 'http://') || str_starts_with($logo, '/')
            ? $logo
            : (is_file(public_path('assets/partners/'.$logo)) ? $image::url('assets/partners/'.$logo) : null))
        : null;
    $storyBlocks = collect($channelStoryBlocks ?? []);
    $firstStory = $storyBlocks->firstWhere('type', 'rich_text');
    $featureList = $storyBlocks->firstWhere('type', 'feature_list');
    $intro = $firstStory['payload']['body'] ?? $description;
    $formats = $featureList['payload']['items'] ?? [];
    $additionalBlocks = $storyBlocks->reject(fn ($block) => $block === $firstStory || $block === $featureList)->values()->all();
    $ownerRoute = \App\Features\Catalog\Support\MediaDirectory::owner($owner['slug'] ?? '');
    $accent = $ownerRoute['accent'] ?? '#500038';
    $accent = preg_match('/\A#[0-9a-fA-F]{6}\z/', $accent) ? $accent : '#500038';
    $srcset = $image::srcset($editorial['image'], [480, 640, 960, 1280]);
    $rateGroups = collect($sections)->groupBy(fn (array $section) => $section['product']['slug'] ?? 'other');
    $rateCount = collect($sections)->reject(fn (array $section) => $section['is_media_value'])->sum(fn (array $section): int => count($section['entries']));
    $related = $siblings->sortByDesc(fn ($sibling) => ($sibling['category'] ?? null) === ($channel['category'] ?? null))->take(8);
@endphp

<div class="channel-editorial" style="--channel-accent:{{ $accent }}">
    <div class="container">
        <nav class="breadcrumb channel-editorial-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
            <a href="{{ $ownerRoute ? route('media.owner', $owner['slug']) : route('media.owners') }}">{{ $owner['name'] }}</a><span aria-hidden="true">/</span>
            <span aria-current="page">{{ $channel['name'] }}</span>
        </nav>

        <header class="channel-editorial-hero">
            <div class="channel-editorial-hero__copy">
                <div class="channel-editorial-identity">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="" decoding="async">
                    @else
                        <div class="channel-editorial-identity__icon"><x-dm-icon :name="$editorial['icon']" :size="36"/></div>
                    @endif
                    <span>{{ $owner['name'] }}<small>{{ $channel['category'] ?? 'Media channel' }}</small></span>
                </div>
                <h1>{{ $channel['name'] }}</h1>
                <p class="channel-editorial-tagline">{{ $editorial['tagline'] }}</p>
                <p class="channel-editorial-intro">{{ $intro }}</p>
                <div class="channel-editorial-actions">
                    <a class="button button--primary" href="{{ route('contact') }}">Discuss your campaign <span aria-hidden="true">↗</span></a>
                    <a class="button button--secondary" href="#channel-rates-title">Explore rates &amp; packages</a>
                </div>
            </div>
            <figure class="channel-editorial-hero__visual">
                <img src="{{ $image::url($editorial['image']) }}" @if ($srcset) srcset="{{ $srcset }}" sizes="(max-width: 740px) 92vw, 49vw" @endif alt="{{ $editorial['illustrated'] ? 'Illustrative media environment' : $channel['name'].' catalogue artwork' }}" fetchpriority="high" decoding="async">
                <figcaption>
                    <span><x-dm-icon :name="$editorial['icon']" :size="20"/> {{ $channel['category'] ?? 'Media channel' }}</span>
                    <small>{{ $editorial['illustrated'] ? 'Illustrative media environment' : 'Catalogue artwork' }}</small>
                </figcaption>
            </figure>
        </header>

        <nav class="channel-editorial-nav" aria-label="Channel sections">
            <a href="#channel-formats"><x-dm-icon name="layers" :size="19"/> Campaign formats</a>
            <a href="#channel-rates-title"><x-dm-icon name="document" :size="19"/> Rates &amp; packages</a>
            @if ($rateCards->isNotEmpty())<a href="#channel-decks"><x-dm-icon name="screen" :size="19"/> Media opportunities</a>@endif
            @if ($related->isNotEmpty())<a href="#channel-network"><x-dm-icon name="users" :size="19"/> Related channels</a>@endif
        </nav>

        <section id="channel-formats" class="channel-editorial-formats" aria-labelledby="channel-formats-title">
            <div class="channel-editorial-heading">
                <div><span class="channel-editorial-kicker">Campaign possibilities</span><h2 id="channel-formats-title">{{ $formats !== [] ? ($featureList['payload']['title'] ?? 'Explore campaign formats') : 'Shape your campaign' }}</h2></div>
                <p>{{ $formats !== [] ? 'Explore the catalogue’s channel-specific formats. Confirm the latest availability and terms before booking.' : 'Ideas to discuss with our team, not confirmed advertising inventory or guaranteed placements.' }}</p>
            </div>
            <div class="channel-editorial-format-grid">
                @foreach ($formats !== [] ? $formats : array_map(fn ($title) => ['title' => $title], $editorial['ideas']) as $format)
                    <article class="channel-editorial-format">
                        <span class="channel-editorial-format__icon"><x-dm-icon :name="[$editorial['icon'], 'target', 'layers', 'calendar'][$loop->index % 4]" :size="25"/></span>
                        <h3>{{ $format['title'] }}</h3>
                        @if (! empty($format['body']))<p>{{ $format['body'] }}</p>@endif
                    </article>
                @endforeach
            </div>
            @if ($additionalBlocks !== [])
                <div class="channel-editorial-additional"><x-content-blocks :blocks="$additionalBlocks"/></div>
            @endif
        </section>

        <section class="channel-editorial-rates" aria-labelledby="channel-rates-title">
            <div class="channel-editorial-heading">
                <div><span class="channel-editorial-kicker">Advertise with {{ $channel['name'] }}</span><h2 id="channel-rates-title">Rates &amp; packages</h2></div>
                <p>Source rate-card references, not live quotations. Rates, taxes, dates and availability require current confirmation.</p>
            </div>
            @if ($sections !== [])
                <div class="channel-editorial-rate-overview">
                    <div><x-dm-icon name="document" :size="32"/><span><strong>{{ $rateCount }}</strong> rate lines <small>Catalogue references, excluding media value</small></span></div>
                    <div><x-dm-icon name="layers" :size="32"/><span><strong>{{ count($sections) }}</strong> rate sections <small>Explore formats and packages</small></span></div>
                    <a class="button button--secondary" href="{{ route('plans.index') }}">Review campaign plans ↗</a>
                </div>
                <div class="channel-editorial-rate-groups">
                    @foreach ($rateGroups as $group)
                        @php($product = $group->first()['product'] ?? null)
                        <section class="channel-editorial-rate-group" aria-labelledby="channel-rate-group-{{ $loop->index }}">
                            <div class="channel-editorial-rate-group__header">
                                <div><h3 id="channel-rate-group-{{ $loop->index }}">{{ $product['name'] ?? 'Rate-card reference' }}</h3>
                                    @if (! empty($product['validity']))<p>Source validity: {{ $product['validity'] }}</p>@endif
                                </div>
                                @if (! empty($product['slug']))<a class="text-link" href="{{ route('offers.show', $product['slug']) }}">Full rate card ↗</a>@endif
                            </div>
                            @foreach ($group as $section)
                                <details class="channel-editorial-rate-detail" @if ($loop->first || $group->count() <= 2) open @endif>
                                    <summary><span>{{ $section['title'] }}</span><small>{{ $section['is_media_value'] ? 'Reference value only' : count($section['entries']).' rate lines' }}</small><x-dm-icon name="arrow" :size="17"/></summary>
                                    <x-rate-section :section="$section" :show-channel="false"/>
                                </details>
                            @endforeach
                        </section>
                    @endforeach
                </div>
            @else
                <div class="channel-editorial-empty">
                    <span class="channel-editorial-format__icon"><x-dm-icon name="document" :size="34"/></span>
                    <div><h3>Rates for {{ $channel['name'] }} are available on request.</h3><p>Tell us the format, audience, timing and budget you have in mind. We'll help you confirm current opportunities.</p></div>
                    <a class="button button--primary" href="{{ route('contact') }}">Request current rates ↗</a>
                </div>
            @endif
        </section>

        @if ($rateCards->isNotEmpty())
            <section id="channel-decks" class="channel-editorial-decks" aria-labelledby="channel-decks-title">
                <div class="channel-editorial-heading">
                    <div><span class="channel-editorial-kicker">Source decks</span><h2 id="channel-decks-title">Explore media opportunities</h2></div>
                    <p>Review the underlying packages and source material for your campaign.</p>
                </div>
                <div class="channel-editorial-deck-grid" data-count="{{ $rateCards->count() }}">
                    @foreach ($rateCards as $offer)
                        @include('pages.owners._editorial-offer', ['offer' => $offer, 'feature' => []])
                    @endforeach
                </div>
            </section>
        @endif

        @if ($related->isNotEmpty())
            <section id="channel-network" class="channel-editorial-network" aria-labelledby="channel-network-title">
                <div class="channel-editorial-heading">
                    <div><span class="channel-editorial-kicker">More from {{ $owner['name'] }}</span><h2 id="channel-network-title">Explore related channels</h2></div>
                    @if ($ownerRoute)<a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">View full network ↗</a>@endif
                </div>
                <div class="channel-editorial-network-grid">
                    @foreach ($related as $sibling)
                        @php
                            $siblingLogo = $sibling['logo'] ?? null;
                            $siblingUrl = $siblingLogo ? (str_starts_with($siblingLogo, 'http://') || str_starts_with($siblingLogo, 'https://') || str_starts_with($siblingLogo, '/') ? $siblingLogo : (is_file(public_path('assets/partners/'.$siblingLogo)) ? $image::url('assets/partners/'.$siblingLogo) : null)) : null;
                        @endphp
                        <a class="channel-editorial-brand" href="{{ isset($sibling['owner_slug']) ? route('media.channel', [$owner['slug'], $sibling['slug']]) : route('media.channel.show', $sibling['slug']) }}">
                            @if ($siblingUrl)<img src="{{ $siblingUrl }}" alt="" loading="lazy" decoding="async">@endif
                            <span>{{ $sibling['name'] }}</span><span aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="channel-editorial-cta" aria-labelledby="channel-cta-title">
            <span class="channel-editorial-cta__icon"><x-dm-icon name="chart" :size="35"/></span>
            <div><span class="channel-editorial-kicker">Plan your next campaign</span><h2 id="channel-cta-title">Put {{ $channel['name'] }} in your media mix.</h2><p>Build a strategy around your audience, objectives and budget.</p></div>
            <div class="channel-editorial-actions"><a class="button button--on-dark" href="{{ route('advisor') }}">Media Strategy Studio ↗</a><a class="button button--outline-light" href="{{ route('quote.builder') }}">Start a proposal</a></div>
        </section>
    </div>
</div>
