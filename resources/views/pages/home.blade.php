@extends('layouts.app')

@section('content')
<section class="home-hero" aria-labelledby="home-title" style="--home-hero-image: url('{{ \App\Support\MediaImage::url('assets/img/hero-media-city.webp') }}'); --home-hero-mobile-image: url('{{ \App\Support\MediaImage::url('assets/img/hero-media-mobile.webp') }}')">
    <div class="home-hero__shade" aria-hidden="true"></div>
    <div class="container home-hero__content">
        <p class="home-hero__eyebrow">Brand <span>·</span> Reach <span>·</span> Engage <span>·</span> Convert</p>
        <h1 id="home-title" class="font-display">All Media.<br><span>Bigger Possibilities.</span></h1>
        <p class="home-hero__summary">TV, Radio, Digital, Outdoor, Print, Creators, Events, AI Solutions and more<br class="home-hero__summary-break"> all in one place.</p>
        <div class="home-hero__actions">
            <a class="btn home-btn-primary" href="{{ route('media.index') }}">Explore Media <span aria-hidden="true">→</span></a>
            <a class="btn home-btn-light" href="{{ route('contact') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11.2a7.2 7.2 0 0 1-7.4 7.2 8 8 0 0 1-3.2-.7L4 19l1.4-4.1a7 7 0 0 1-.9-3.5A7.3 7.3 0 0 1 12 4a7.3 7.3 0 0 1 8 7.2Z"></path></svg>
                Talk to Our Team
            </a>
        </div>
    </div>
</section>

<section class="home-search" aria-label="Discover media">
    <div class="container">
        <div class="home-search__panel">
            <nav class="home-search__tabs" aria-label="Explore Dunia Media">
                <a class="is-active" href="{{ route('media.index') }}" aria-current="page">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"></circle><path d="m15.5 15.5 4 4"></path></svg>
                    Find Media
                </a>
                <a href="{{ route('creators.index') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="5" r="2.2"></circle><circle cx="6" cy="17" r="2.2"></circle><circle cx="18" cy="17" r="2.2"></circle><path d="m11 7-4 8m6-8 4 8m-8 2h6"></path></svg>Find Creators</a>
                <a href="{{ route('solutions') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2 1.9 6.1L20 10l-6.1 1.9L12 18l-1.9-6.1L4 10l6.1-1.9L12 2Zm6 13 .9 2.1L21 18l-2.1.9L18 21l-.9-2.1L15 18l2.1-.9L18 15Z"></path></svg>AI Solutions</a>
                <a href="{{ route('academy') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5" width="17" height="16" rx="2"></rect><path d="M7.5 3v4m9-4v4m-13 3h17"></path></svg>Training &amp; Events</a>
                <a href="{{ route('assets.join') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"></rect><path d="M9 4v16m6-16v16M4 9h16m-16 6h16"></path></svg>Custom Media</a>
            </nav>

            <form action="{{ route('media.index') }}" method="get" class="home-search__form">
                <label>
                    <span>Media Type</span>
                    <select name="type">
                        <option value="all">All Media Types</option>
                        @foreach(['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Digital', 'Creators', 'Print', 'Podcast'] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span>Target Audience</span>
                    <input type="search" name="q" placeholder="e.g. families, commuters">
                </label>
                <label>
                    <span>Location</span>
                    <select name="location">
                        <option value="all">All Locations</option>
                        <option value="Nationwide">Nationwide</option>
                        <option value="Klang Valley">Klang Valley</option>
                    </select>
                </label>
                <label>
                    <span>Budget Range</span>
                    <select name="budget">
                        <option value="any">Select Budget</option>
                        <option value="10000">Up to RM10,000</option>
                        <option value="50000">RM10,000 – RM50,000</option>
                        <option value="100000">RM50,000 – RM100,000</option>
                    </select>
                </label>
                <button class="btn btn-primary home-search__submit" type="submit">Find Media <span aria-hidden="true">→</span></button>
            </form>
        </div>
    </div>
</section>

@php($mediaOwners = require resource_path('data/media-owners.php'))
@php($rosterOwnerCount = count($mediaOwners))
@php($rosterOutletCount = collect($mediaOwners)->sum(fn ($owner) => collect($owner['categories'])->sum(fn ($category) => count($category['brands']))))

<section class="home-network" aria-labelledby="home-network-title">
    <div class="container home-network__inner">
        <div class="home-network__heading">
            <div>
                <span class="home-network__eyebrow">Media owners &amp; outlets</span>
                <h2 id="home-network-title">The media network behind your next campaign</h2>
                <p>Television, radio, publishing, digital, outdoor, cinema, airports and rail — together in one view.</p>
            </div>
            <div class="home-network__proof">
                <div class="home-network__metric"><strong>{{ $rosterOwnerCount }}</strong><span>media owners</span></div>
                <span class="home-network__proof-rule" aria-hidden="true"></span>
                <div class="home-network__metric"><strong>{{ $rosterOutletCount }}</strong><span>outlets</span></div>
                <a href="{{ route('media.index') }}">Explore inventory <span aria-hidden="true">→</span></a>
            </div>
        </div>

        <div class="home-network__owners">
            @foreach($mediaOwners as $ownerIndex => $owner)
                @php($outletCount = collect($owner['categories'])->sum(fn ($category) => count($category['brands'])))
                <article
                    class="home-owner {{ $outletCount >= 8 ? 'home-owner--featured' : 'home-owner--compact' }} @if(($owner['tone'] ?? '') === 'astro') home-owner--astro @endif"
                    style="--owner-accent: {{ $owner['accent'] ?? '#287c76' }}"
                    aria-labelledby="home-owner-title-{{ $ownerIndex }}"
                >
                    <header class="home-owner__header">
                        <div class="home-owner__identity">
                            <div class="home-owner__logo">
                                @if(($owner['visual'] ?? '') === 'karangkraf')
                                    <span class="home-owner__art home-owner__art--karangkraf" style="--owner-art-image: url('{{ \App\Support\MediaImage::url('assets/partners/'.$owner['logo']) }}')" aria-hidden="true"></span>
                                @elseif(!empty($owner['logo']))
                                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$owner['logo']) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="home-owner__wordmark" aria-hidden="true">{{ $owner['mark'] ?? $owner['name'] }}</span>
                                @endif
                            </div>
                            <div>
                                <span class="home-owner__eyebrow">Media owner</span>
                                <h3 id="home-owner-title-{{ $ownerIndex }}">{{ $owner['name'] }}</h3>
                            </div>
                        </div>
                        <div class="home-owner__outlet-count" aria-label="{{ $outletCount }} listed outlets">
                            <strong>{{ $outletCount }}</strong>
                            <span>outlets</span>
                        </div>
                    </header>

                    <div class="home-owner__outlets">
                        @foreach($owner['categories'] as $category)
                            <section class="home-owner__category @if(count($category['brands']) > 6) home-owner__category--wide @endif" aria-label="{{ $category['label'] }}">
                                <h4>{{ $category['label'] }}</h4>
                                <ul class="home-brand-grid">
                                    @foreach($category['brands'] as $outlet)
                                        <li class="home-brand">
                                            <div class="home-brand__mark @if(empty($outlet['logo'])) home-brand__mark--wordmark @endif">
                                                @if(!empty($outlet['logo']))
                                                    <img src="{{ \App\Support\MediaImage::url('assets/partners/'.$outlet['logo']) }}" alt="" loading="lazy" decoding="async">
                                                @else
                                                    <span>{{ $outlet['name'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($outlet['logo']))
                                                <span class="home-brand__name">{{ $outlet['name'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="home-stats" aria-label="Dunia Media at a glance">
    <div class="container home-stats__grid">
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><rect x="4" y="6" width="19" height="15" rx="2"></rect><path d="M9 26h10m-5-5v5m11-14h4v12h-4z"></path></svg></span><p><strong>100+</strong><span>Media Owners &amp; Partners</span></p></div>
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><path d="M16 4v4m0 16v4m12-12h-4M8 16H4m20.5-8.5-3 3m-11 11-3 3m17 0-3-3m-11-11-3-3"></path><circle cx="16" cy="16" r="7"></circle><path d="M13 16h6m-3-3v6"></path></svg></span><p><strong>10,000+</strong><span>Media Inventory Options</span></p></div>
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><circle cx="16" cy="10" r="4"></circle><circle cx="7" cy="13" r="3"></circle><circle cx="25" cy="13" r="3"></circle><path d="M8 27v-3a8 8 0 0 1 16 0v3zm-6 0v-3a5 5 0 0 1 7-4.6M30 27v-3a5 5 0 0 0-7-4.6"></path></svg></span><p><strong>1,000+</strong><span>Creators &amp; Influencers</span></p></div>
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><path d="m16 3 4 3 5 .4.4 5 3 4-3 4-.4 5-5 .4-4 3-4-3-5-.4-.4-5-3-4 3-4 .4-5 5-.4z"></path><path d="m12 16 3 3 6-7"></path></svg></span><p><strong>500+</strong><span>Successful Campaigns</span></p></div>
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><rect x="4" y="6" width="24" height="17" rx="2"></rect><path d="M10 27h12m-6-4v4m-8-13 5-4 4 5 4-3 3 2"></path></svg></span><p><strong>50+</strong><span>Training &amp; AI Workshops</span></p></div>
        <div class="home-stat"><span class="home-stat__icon" aria-hidden="true"><svg viewBox="0 0 32 32"><path d="M9 4h11l6 6v18H9z"></path><path d="M20 4v7h6M13 16h9m-9 4h9m-9 4h6M5 8v20"></path></svg></span><p><strong>1 Platform</strong><span>Endless Possibilities</span></p></div>
    </div>
</section>

<section class="home-channels" aria-labelledby="channels-title">
    <div class="container home-channels__layout">
        <div class="home-channels__intro">
            <h2 id="channels-title">Explore Our<br> Media Channels</h2>
            <p>From traditional to digital, reach your audience everywhere.</p>
            <a href="{{ route('media.index') }}">View All Media <span aria-hidden="true">→</span></a>
        </div>
        <div class="home-channel-grid">
            @foreach([
                ['TV & Broadcast', 'Astro, TV3, RTM and more', 'tv', 'TV & Broadcast'],
                ['Radio', 'Nationwide & local stations', 'radio', 'Radio'],
                ['Billboard & Outdoor', 'Traditional, LED and transit', 'outdoor', 'Outdoor'],
                ['Digital & Online', 'Web, social media and display', 'digital', 'Digital'],
                ['Print & Publishing', 'Newspapers and magazines', 'print', 'Print'],
                ['In-Premise Media', 'Retail, F&B and custom spaces', 'indoor', 'Indoor'],
                ['Influencers & Talent', 'Niche creators and celebrities', 'creators', 'Creators'],
            ] as [$title, $description, $image, $type])
                <a class="home-channel" href="{{ route('media.index', ['type' => $type]) }}">
                    <img
                        src="{{ \App\Support\MediaImage::url('assets/img/'.$image.'.webp') }}"
                        srcset="{{ \App\Support\MediaImage::srcset('assets/img/'.$image.'.webp', [360, 480, 720, 960]) }}"
                        sizes="(max-width: 700px) 44vw, (max-width: 1180px) 23vw, 12vw"
                        alt=""
                        width="1200"
                        height="675"
                        loading="lazy"
                        decoding="async"
                    >
                    <span class="home-channel__body">
                        <strong>{{ $title }}</strong>
                        <small>{{ $description }}</small>
                        <span class="home-channel__arrow" aria-hidden="true">→</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="home-promos" aria-label="More from Dunia Media">
    <div class="container home-promos__grid">
        <article class="home-promo home-promo--solutions">
            <span class="home-promo__symbol" aria-hidden="true">✦</span>
            <div><h2>Complete Marketing Solutions</h2><p>Creative, production, branding and technology, connected around your goals.</p><a href="{{ route('services') }}">Explore Solutions <span aria-hidden="true">→</span></a></div>
        </article>
        <article class="home-promo home-promo--image">
            <img src="{{ \App\Support\MediaImage::url('assets/img/training.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/training.webp', [160, 256, 320, 512]) }}" sizes="(max-width: 700px) 31vw, 8vw" alt="Business workshop" width="1280" height="1280" loading="lazy" decoding="async">
            <div><h2>AI Seminar &amp; Training</h2><p>Practical workshops for modern teams.</p><a href="{{ route('academy') }}">View Upcoming Events <span aria-hidden="true">→</span></a></div>
        </article>
        <article class="home-promo home-promo--image">
            <img src="{{ \App\Support\MediaImage::url('assets/img/creator-feature.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/creator-feature.webp', [160, 256, 320, 512]) }}" sizes="(max-width: 700px) 31vw, 8vw" alt="Content creator at work" width="640" height="640" loading="lazy" decoding="async">
            <div><h2>Creators Network</h2><p>Find voices that fit your campaign.</p><a href="{{ route('creators.index') }}">Explore Creators <span aria-hidden="true">→</span></a></div>
        </article>
        <article class="home-promo home-promo--custom">
            <img src="{{ \App\Support\MediaImage::url('assets/img/custom-media.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/custom-media.webp', [160, 256, 320, 512]) }}" sizes="(max-width: 700px) 39vw, 10vw" alt="A custom media installation" width="768" height="768" loading="lazy" decoding="async">
            <div><h2>Need a Custom Media Solution?</h2><p>We can turn any space into a media opportunity.</p><a href="{{ route('assets.join') }}">Talk to Our Team <span aria-hidden="true">→</span></a></div>
        </article>
    </div>
</section>

@if($media->isNotEmpty())
    <section class="section section-soft">
        <div class="container">
            <div class="section-heading">
                <div><span class="eyebrow">MEDIA DISCOVERY</span><h2>Ideas worth exploring.</h2><p>Explore sample opportunities, then request confirmed inventory and pricing.</p></div>
                <a class="text-link" href="{{ route('media.index') }}">Browse marketplace <span aria-hidden="true">→</span></a>
            </div>
            <div class="cards-3">@foreach($media as $item)<x-media-card :item="$item" />@endforeach</div>
            <p class="demo-note">Illustrative inventory: sample opportunities are shown for platform demonstration and are not confirmed live availability or supplier agreements.</p>
        </div>
    </section>
@endif

@if($articles->isNotEmpty())
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div><span class="eyebrow">IDEAS &amp; PERSPECTIVES</span><h2>Insights for what's next.</h2><p>Learn how media, creativity and technology can work better together.</p></div>
                <a class="text-link" href="{{ route('insights') }}">Read all insights <span aria-hidden="true">→</span></a>
            </div>
            <div class="cards-3">
                @foreach($articles as $article)
                    <article class="article-card">
                        @php($articleImageSrcset = \App\Support\MediaImage::srcset($article->image, [360, 480, 720, 960]))
                        <a href="{{ route('insights.show', $article->slug) }}"><img src="{{ \App\Support\MediaImage::url($article->image) }}" @if($articleImageSrcset) srcset="{{ $articleImageSrcset }}" sizes="(max-width: 700px) 90vw, (max-width: 980px) 45vw, (max-width: 1280px) 30vw, 28vw" @endif loading="lazy" decoding="async" alt="{{ $article->title }}"></a>
                        <div><span class="eyebrow">{{ strtoupper($article->category) }}</span><h3><a href="{{ route('insights.show', $article->slug) }}">{{ $article->title }}</a></h3><p>{{ $article->excerpt }}</p><a href="{{ route('insights.show', $article->slug) }}" class="text-link">Read story <span aria-hidden="true">→</span></a></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
