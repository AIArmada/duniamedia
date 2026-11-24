@php
    $hero = $heroImage ?? null;
    $heroUrl = $hero ? \App\Features\Catalog\Support\MediaImage::url($hero) : null;
    $logo = $channel['logo'] ?? null;
    $logoUrl = $logo
        ? (str_starts_with($logo, 'http') || str_starts_with($logo, '/')
            ? $logo
            : \App\Features\Catalog\Support\MediaImage::url('assets/partners/'.$logo))
        : null;
    $storyBlocks = $channelStoryBlocks ?? (empty($channelStory) ? [] : [[
        'type' => 'rich_text',
        'payload' => ['body' => $channelStory],
    ]]);
    $ownerRoute = \App\Features\Catalog\Support\MediaDirectory::owner($owner['slug'] ?? '');
@endphp

<section class="directory-hero directory-hero--channel">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.index') }}">Media</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
            <a href="{{ $ownerRoute ? route('media.owner', $owner['slug']) : route('media.owners') }}">{{ $owner['name'] }}</a><span aria-hidden="true">/</span>
            <span aria-current="page">{{ $channel['name'] }}</span>
        </nav>

        <div class="directory-hero__head">
            <div>
                <div class="directory-hero__logo">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $channel['name'] }}">
                    @else
                        <span>{{ $channel['name'] }}</span>
                    @endif
                </div>
                <span class="eyebrow">{{ $channel['category'] ?? 'Channel' }}</span>
                <h1>{{ $channel['name'] }}</h1>
                <p class="directory-hero__lede">{{ $description }}</p>
                <x-stat-grid :stats="$channelStats ?? []"/>
            </div>
            <div class="directory-hero__actions">
                <a class="button button--primary" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                <a class="button button--secondary" href="{{ route('contact') }}">Request rates</a>
            </div>
        </div>
    </div>
</section>

@if ($heroUrl)
    <figure class="channel-hero-media">
        <img src="{{ $heroUrl }}" alt="{{ $channel['name'] }}" loading="lazy" decoding="async">
    </figure>
@endif

@if (! empty($storyBlocks))
    <section class="section section--cream">
        <div class="container container--narrow">
            <x-content-blocks :blocks="$storyBlocks"/>
        </div>
    </section>
@endif

<section class="section" aria-labelledby="channel-rates-title">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Rates &amp; packages</span>
                <h2 id="channel-rates-title" class="section-title">Ways to appear on {{ $channel['name'] }}.</h2>
                <p>Indicative rates, formats and package detail. Final availability and terms are confirmed by the media owner.</p>
            </div>
        </div>

        @if (! empty($sections))
            <div class="rate-sections">
                @foreach ($sections as $section)
                    <x-rate-section :section="$section" :show-channel="false"/>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="text-align:left">
                <span class="eyebrow">Tailored proposal</span>
                <h3>Rates for {{ $channel['name'] }} are available on request.</h3>
                <p style="margin-left:0">We will confirm current slots, formats and availability for this channel.</p>
                <div class="empty-state__actions" style="justify-content:flex-start">
                    <a class="button button--primary" href="{{ route('contact') }}">Request rates <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        @endif
    </div>
</section>

@if ($rateCards->isNotEmpty())
    <section class="section section--cream">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Source decks</span>
                    <h2 class="section-title">More from {{ $channel['name'] }}.</h2>
                </div>
            </div>
            <div class="media-grid">
                @foreach ($rateCards as $item)
                    <x-media-card :item="$item"/>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if ($siblings->isNotEmpty())
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">More from {{ $owner['name'] }}</span>
                    <h2 class="section-title">Explore the group.</h2>
                </div>
                @if ($ownerRoute)
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('media.owner', $owner['slug']) }}">View all channels <span aria-hidden="true">→</span></a>
                    </div>
                @endif
            </div>
            <div class="directory-card-grid">
                @foreach ($siblings as $sibling)
                    <a class="directory-card" href="{{ isset($sibling['owner_slug']) ? route('media.channel', [$owner['slug'], $sibling['slug']]) : route('media.channel.show', $sibling['slug']) }}">
                        <span class="directory-card__mark">
                            @if (! empty($sibling['logo']))
                                @php($siblingLogo = str_starts_with($sibling['logo'], 'http') || str_starts_with($sibling['logo'], '/')
                                    ? $sibling['logo']
                                    : \App\Features\Catalog\Support\MediaImage::url('assets/partners/'.$sibling['logo']))
                                <img src="{{ $siblingLogo }}" alt="" loading="lazy">
                            @else
                                {{ $sibling['name'] }}
                            @endif
                        </span>
                        <span class="directory-card__name">{{ $sibling['name'] }}</span>
                        <span class="directory-card__arrow" aria-hidden="true">↗</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

<section class="section section--dark">
    <div class="container">
        <div class="cta-panel__inner">
            <div>
                <span class="eyebrow eyebrow--pale">Plan this channel</span>
                <h2 class="section-title">Put {{ $channel['name'] }} in your next campaign.</h2>
                <p style="max-width:560px;margin-top:0.7rem;color:rgba(255,248,247,.66)">Tell us the audience and outcome; we will shape the format, schedule and budget.</p>
            </div>
            <div class="cta-panel__actions">
                <a class="button button--on-dark" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                <a class="button button--outline-light" href="{{ route('contact') }}">Talk to a specialist</a>
            </div>
        </div>
    </div>
</section>
