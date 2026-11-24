@php
    $logo = $owner['logo'] ?? null;
    $logoUrl = $logo
        ? \App\Features\Catalog\Support\MediaImage::url(
            str_starts_with($logo, 'http') || str_starts_with($logo, '/') ? $logo : 'assets/partners/'.$logo
        )
        : null;
    $storyBlocks = $ownerStoryBlocks ?? (empty($ownerStory) ? [] : [[
        'type' => 'rich_text',
        'payload' => ['body' => $ownerStory],
    ]]);
    $ownerLede = $storyBlocks[0]['payload']['body'] ?? $owner['description'];
@endphp

<section class="directory-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.index') }}">Media</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
            <span aria-current="page">{{ $owner['name'] }}</span>
        </nav>

        <div class="directory-hero__head">
            <div class="directory-hero__main">
                <div class="directory-hero__logo">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $owner['name'] }}">
                    @else
                        <span>{{ $owner['mark'] ?? $owner['name'] }}</span>
                    @endif
                </div>
                <div class="directory-hero__intro">
                    <span class="eyebrow">Media owner</span>
                    <h1>{{ $owner['name'] }}</h1>
                    <p class="directory-hero__lede">{{ $ownerLede }}</p>
                    <x-stat-grid :stats="$ownerStats ?? []"/>
                </div>
            </div>
            <div class="directory-hero__actions">
                <a class="button button--primary" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                <a class="button button--secondary" href="{{ route('contact') }}">Talk to a specialist</a>
            </div>
        </div>
    </div>
</section>

@if (! empty($storyBlocks))
    <section class="section section--cream">
        <div class="container container--narrow">
            <x-content-blocks :blocks="$storyBlocks"/>
        </div>
    </section>
@endif

@if ($rateCards->isNotEmpty())
    <section class="section" id="rate-cards" aria-labelledby="rate-cards-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Rate cards &amp; packages</span>
                    <h2 id="rate-cards-title" class="section-title">Current opportunities.</h2>
                    <p>Structured rates, formats and channel detail — all available in the portal.</p>
                </div>
                <div class="section-heading__aside">
                    <a class="text-link" href="{{ route('media.index') }}">Browse all media <span aria-hidden="true">→</span></a>
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

<section class="section section--cream" aria-labelledby="roster-title">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">The roster</span>
                <h2 id="roster-title" class="section-title">Channels under {{ $owner['name'] }}.</h2>
                <p>Each channel has its own page with audience context and structured rates.</p>
            </div>
        </div>

        @foreach ($owner['categories'] as $category)
            <section class="directory-category" aria-label="{{ $category['label'] }}">
                <div class="directory-category__head">
                    <h3>{{ $category['label'] }}</h3>
                    <span>{{ count($category['brands']) }} {{ count($category['brands']) === 1 ? 'channel' : 'channels' }}</span>
                </div>
                <div class="directory-card-grid">
                    @foreach (collect($channels)->where('category', $category['label']) as $channel)
                        <a class="directory-card" href="{{ route('media.channel', [$owner['slug'], $channel['slug']]) }}">
                            <span class="directory-card__mark">
                                @if (! empty($channel['logo']))
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url('assets/partners/'.$channel['logo']) }}" alt="" loading="lazy">
                                @else
                                    {{ $channel['name'] }}
                                @endif
                            </span>
                            <span class="directory-card__name">{{ $channel['name'] }}</span>
                            <span class="directory-card__arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</section>

<section class="section section--dark">
    <div class="container">
        <div class="cta-panel__inner">
            <div>
                <span class="eyebrow eyebrow--pale">Plan across the group</span>
                <h2 class="section-title">Build a cross-channel proposal.</h2>
                <p style="max-width:560px;margin-top:0.7rem;color:rgba(255,248,247,.66)">Combine TV, radio, digital and out-of-home from {{ $owner['name'] }} in one brief.</p>
            </div>
            <div class="cta-panel__actions">
                <a class="button button--on-dark" href="{{ route('quote.builder') }}">Start a proposal <span aria-hidden="true">↗</span></a>
                <a class="button button--outline-light" href="{{ route('contact') }}">Talk to a specialist</a>
            </div>
        </div>
    </div>
</section>
