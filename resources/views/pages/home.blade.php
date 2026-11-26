@extends('layouts.app')

@section('content')
    @php
        $directory = \App\Features\Catalog\Support\MediaDirectory::class;
        $image = \App\Features\Catalog\Support\MediaImage::class;
        $hot = json_decode(file_get_contents(resource_path('data/hot-fm-profile.json')), true, flags: JSON_THROW_ON_ERROR);
        $mediums = [
            ['TV', 'TV & Broadcast', 'tv'], ['Radio', 'Radio', 'radio'], ['Outdoor', 'Outdoor', 'billboard'],
            ['Digital', 'Digital', 'digital'], ['Print', 'Print', 'print'], ['Creators', 'Creators', 'users'], ['Custom spaces', 'Indoor', 'spaces'],
        ];
        $tools = [
            ['Media Explorer', 'Discover channels and advertising opportunities.', 'compass', 'media.index'],
            ['Media Rate Exchange', 'Compare indicative rates and formats.', 'layers', 'rates.index'],
            ['Media Strategy Studio', 'Find a media mix for your brief and budget.', 'chart', 'advisor'],
            ['Campaign Plans', 'Save and organise your shortlisted media.', 'briefcase', 'plans.index'],
            ['Proposal Studio', 'Build a proposal and request a quotation.', 'document', 'quote.builder'],
        ];
        $brands = [
            ['owner' => 'media-prima', 'channel' => 'hot-fm', 'label' => 'Radio · Personality-led campaigns', 'photo' => 'hot-fm-activation.webp', 'alt' => 'Hot FM Zoomerz community activation from the broadcaster’s sponsorship deck', 'detail' => $hot['audience']['value'].' weekly listeners', 'icon' => 'radio'],
            ['owner' => 'media-prima', 'channel' => 'tv3', 'label' => 'TV · News & entertainment', 'photo' => 'tv3-newsroom.webp', 'alt' => 'Buletin TV3 studio artwork from the Podcast Utama media deck', 'detail' => 'Broadcast & programme opportunities', 'icon' => 'tv'],
            ['owner' => 'media-prima', 'channel' => 'big-tree', 'label' => 'Outdoor · Landmark locations', 'photo' => 'big-tree-klcc.webp', 'alt' => 'Big Tree KLCC lightbox displays photographed for the media deck', 'detail' => 'Explore KLCC & other locations', 'icon' => 'billboard'],
            ['owner' => 'karangkraf', 'channel' => 'sinar-harian', 'label' => 'Print · Digital news', 'photo' => 'sinar-editorial.webp', 'alt' => 'Sinar Karangkraf advertising solutions artwork from its media deck', 'detail' => 'News, print & digital formats', 'icon' => 'print'],
        ];
        $ownerSlugs = ['media-prima', 'astro', 'rtm', 'bernama', 'star-media-group', 'karangkraf', 'klia', 'al-hijrah'];
        $money = static fn ($price): string => 'RM '.number_format((float) $price, fmod((float) $price, 1.0) === 0.0 ? 0 : 2);
    @endphp

    <div class="market-home">
        <section class="market-hero" aria-labelledby="home-title">
            <div class="market-hero__copy">
                <h1 id="home-title">Discover, Compare &amp;<br class="market-desktop-break"> Plan Media in Malaysia.</h1>
                <p>DuniaMedia helps brands discover media owners, programmes, personalities, advertising opportunities and campaign tools — all in one place.</p>
                <form method="GET" action="{{ route('search') }}" class="market-search" role="search">
                    <label class="sr-only" for="home-search">Search DuniaMedia</label>
                    <x-dm-icon name="search" :size="23"/>
                    <input id="home-search" name="q" type="search" placeholder="Search media, owners, programmes or rates…" autocomplete="off" required>
                    <button type="submit" aria-label="Search"><x-dm-icon name="search" :size="22"/></button>
                </form>
                <nav class="market-quick" aria-label="Quick media filters">
                    <span>Quick explore</span><a href="{{ route('media.owners') }}">Media owners</a>
                    @foreach ($mediums as [$label, $medium, $icon])
                        <a href="{{ route('media.index', ['medium' => $medium]) }}">{{ $label }}</a>
                    @endforeach
                    <a href="{{ route('rates.index') }}">Rates</a><a href="{{ route('plans.index') }}">Campaign plans</a>
                </nav>
            </div>
            <div class="market-hero__brands" aria-label="Explore media brands">
                @foreach ($brands as $brand)
                    @php($channel = $directory::channel($brand['owner'], $brand['channel']))
                    @if ($channel)
                        <a class="market-hero-brand" href="{{ route('media.channel', [$brand['owner'], $brand['channel']]) }}">
                            <div class="market-hero-brand__visual">
                                <img src="{{ $image::url('assets/home/'.$brand['photo']) }}" alt="{{ $brand['alt'] }}" width="800" height="450" @if ($loop->first) fetchpriority="high" @else decoding="async" @endif>
                            </div>
                            <div class="market-hero-brand__body">
                                <div class="market-hero-brand__heading">
                                    @if (! empty($channel['logo']))
                                        <img src="{{ $image::url('assets/partners/'.$channel['logo']) }}" alt="{{ $channel['name'] }}" width="112" height="35" decoding="async">
                                    @else
                                        <strong>{{ $channel['name'] }}</strong>
                                    @endif
                                    <span class="market-hero-brand__arrow" aria-hidden="true">→</span>
                                </div>
                                <span>{{ $brand['label'] }}</span>
                                <strong class="market-hero-brand__detail"><x-dm-icon :name="$brand['icon']" :size="15"/>{{ $brand['detail'] }}</strong>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </section>

        <nav class="market-tools" aria-label="Campaign workspaces">
            @foreach ($tools as [$title, $copy, $icon, $route])
                <a class="market-tool" href="{{ route($route) }}"><span class="market-icon"><x-dm-icon :name="$icon" :size="28"/></span><div><strong>{{ $title }}</strong><p>{{ $copy }}</p></div><span class="market-arrow" aria-hidden="true">→</span></a>
            @endforeach
        </nav>

        <section class="market-section" aria-labelledby="market-owners-title">
            <header class="market-heading"><div><h2 id="market-owners-title">Explore Malaysia’s media landscape.</h2><p>Broadcasters, publishers and networks — find the brands behind the media.</p></div><a href="{{ route('media.owners') }}">View all media owners →</a></header>
            <div class="market-owner-grid">
                @foreach ($ownerSlugs as $slug)
                    @php($owner = $directory::owner($slug))
                    @if ($owner)
                        <a class="market-owner" href="{{ route('media.owner', $slug) }}">
                            @if (! empty($owner['logo']))
                                <img src="{{ $image::url('assets/partners/'.$owner['logo']) }}" alt="" width="130" height="55" loading="lazy" decoding="async">
                            @endif
                            <strong>{{ $owner['mark'] ?? $owner['name'] }}</strong><span>{{ count($owner['channels']) }} listed channels</span>
                        </a>
                    @endif
                @endforeach
            </div>
            <nav class="market-mediums" aria-label="Browse by medium">
                @foreach ($mediums as [$label, $medium, $icon])
                    <a href="{{ route('media.index', ['medium' => $medium]) }}"><x-dm-icon :name="$icon" :size="22"/>{{ $label }}<span aria-hidden="true">→</span></a>
                @endforeach
            </nav>
        </section>

        <section class="market-section" aria-labelledby="market-intelligence-title">
            <header class="market-heading"><div><h2 id="market-intelligence-title">What actually sells attention.</h2><p>Discover the audiences, personalities, programmes and places behind the placements.</p></div></header>
            <div class="market-intelligence">
                <article class="market-intel market-intel--radio">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/home/hot-fm-community.webp') }}" alt="Hot FM Zoomerz community activation" width="378" height="212" loading="lazy" decoding="async"><span class="market-intel__tag">Radio</span></div>
                    <div class="market-intel__body"><span class="market-kicker">Radio audience</span><h3>Hot FM</h3><strong>{{ $hot['audience']['value'] }} weekly listeners</strong><p>{{ $hot['audience']['method'] }}</p><a href="{{ route('media.channel', ['media-prima', 'hot-fm']) }}">Explore Hot FM →</a><a class="market-source" href="{{ $hot['audience']['source'] }}" target="_blank" rel="noopener noreferrer">Source: Media Prima Audio ↗</a></div>
                </article>
                <article class="market-intel market-intel--programme">
                    <div class="market-intel__visual market-intel__visual--programme"><div class="market-programme-mark"><img src="{{ $image::url('assets/partners/mp-hotfm-v2.png') }}" alt="" width="100" height="35" loading="lazy"><strong>Bekpes <em>Hot</em></strong><div class="market-soundwave" aria-hidden="true">@foreach ([16, 36, 22, 48, 29, 62, 38, 20, 45, 58, 25, 44, 18, 35] as $bar)<i style="height:{{ $bar }}px"></i>@endforeach</div></div><span class="market-intel__tag">Programme</span></div>
                    <div class="market-intel__body"><span class="market-kicker">Programme &amp; personalities</span><h3>{{ $hot['programmes'][0]['title'] }}</h3><strong>{{ $hot['programmes'][0]['hosts'] }}</strong><p>Weekday breakfast · {{ $hot['programmes'][0]['time'] }}. Explore programme sponsorship and branded conversations.</p><a href="{{ route('media.channel', ['media-prima', 'hot-fm']) }}#hotfm-programme-bekpes-hot">Meet the presenters →</a><a class="market-source" href="{{ $hot['programmes'][0]['source'] }}" target="_blank" rel="noopener noreferrer">Official programme ↗</a></div>
                </article>
                <article class="market-intel market-intel--tv">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/home/tv3-podcast.webp') }}" alt="Podcast Utama studio discussion from the Buletin TV3 deck" width="500" height="281" loading="lazy" decoding="async"><span class="market-intel__tag">TV &amp; podcast</span></div>
                    <div class="market-intel__body"><span class="market-kicker">Television</span><h3>TV3</h3><strong>News, drama &amp; entertainment</strong><p>Explore TV spots, studio formats, programme integrations and sponsorships including Buletin TV3’s Podcast Utama.</p><a href="{{ route('media.channel', ['media-prima', 'tv3']) }}">Explore TV3 →</a></div>
                </article>
                <article class="market-intel market-intel--outdoor">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/home/big-tree-klcc-placement.webp') }}" alt="Big Tree illuminated display at KLCC" width="1000" height="640" loading="lazy" decoding="async"><span class="market-intel__tag">Outdoor</span></div>
                    <div class="market-intel__body"><span class="market-kicker">Out-of-home</span><h3>Big Tree</h3><strong>Landmark advertising locations</strong><p>Discover outdoor formats and placement opportunities including KLCC and KL Sentral.</p><a href="{{ route('media.channel', ['media-prima', 'big-tree']) }}">Explore Big Tree →</a></div>
                </article>
            </div>
        </section>

        <section class="market-section" aria-labelledby="market-work-title">
            <header class="market-heading"><div><h2 id="market-work-title">Proof from real campaigns.</h2><p>Real productions and collaborations, from the RA Media archive.</p></div><a href="{{ route('work.index') }}">View more work →</a></header>
            <div class="market-work-grid">
                @foreach ($featuredFilms as $film)
                    <a class="market-work-card" href="{{ route('work.index') }}#work-media-{{ $film['key'] }}">
                        <div class="market-work-card__top">
                            <div class="market-work-card__visual"><img src="{{ $image::url($film['poster']) }}" alt="" width="960" height="540" loading="lazy" decoding="async"><span class="market-work-play"><x-dm-icon name="play" :size="24"/></span></div>
                            <div class="market-work-card__facts"><div><x-dm-icon name="calendar" :size="19"/><strong>{{ $film['date'] }}</strong></div><div><x-dm-icon name="play" :size="19"/><strong>{{ $film['duration'] }}</strong></div></div>
                        </div>
                        <div class="market-work-card__body"><h3>{{ $film['title'] }}</h3><p>{{ $film['description'] }}</p><span class="market-link">Watch the campaign →</span></div>
                    </a>
                @endforeach
            </div>
            @if ($trustClients->isNotEmpty())
                <div class="market-client-strip"><strong>Selected clients from our archive</strong><div>
                    @foreach ($trustClients as $client)
                        <a href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="Work for {{ $client['name'] }}"><img src="{{ $image::url($client['logo']) }}" alt="{{ $client['name'] }}" width="100" height="48" loading="lazy"></a>
                    @endforeach
                </div></div>
            @endif
        </section>

        <section class="market-section" aria-labelledby="market-rates-title">
            <header class="market-heading"><div><h2 id="market-rates-title">Compare opportunities, not just impressions.</h2><p>Indicative catalogue rates, clear formats and real buying options.</p></div><a href="{{ route('rates.index') }}">Open Rate Exchange →</a></header>
            <div class="market-rates">
                <aside class="market-rates__intro"><span class="market-icon"><x-dm-icon name="layers" :size="32"/></span><h3>Media Rate Exchange</h3><p>Compare actual rate lines from available media decks, then build your shortlist.</p><a href="{{ route('rates.index') }}">Compare rates →</a></aside>
                <div class="market-rates__content">
                    <nav class="market-rate-filters" aria-label="Filter rates in Rate Exchange">
                        @foreach (array_slice($mediums, 0, 6) as [$label, $medium, $icon])
                            <a href="{{ route('rates.index', ['medium' => $medium]) }}">{{ $label }} →</a>
                        @endforeach
                    </nav>
                    @if ($ratePreview !== [])
                        <div class="market-rate-table">
                            <div class="market-rate-head" aria-hidden="true"><span>Placement</span><span>Indicative rate</span><span>Unit</span><span>Action</span></div>
                            @foreach ($ratePreview as $rate)
                                <article class="market-rate-row">
                                    <div><span class="market-kicker">{{ $rate->channelName ?? $rate->channelMedium }}</span><a href="{{ route('offers.show', $rate->productSlug) }}">{{ $rate->item }}</a><small>{{ $rate->productName }}</small></div>
                                    <strong>{{ $money($rate->price) }}</strong><span class="market-rate-unit">{{ $rate->unit ?? 'Per placement' }}</span>
                                    <form method="POST" action="{{ route('plans.rate.add', $rate->id) }}">@csrf<button class="market-primary" type="submit" aria-label="Add {{ $rate->item }} to plan">+ Add to plan</button></form>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="market-empty"><strong>Find a rate that fits your brief.</strong><p>Browse the rate catalogue or ask our team for a current quotation.</p><a href="{{ route('rates.index') }}">Browse rates →</a></div>
                    @endif
                    <p class="market-note">Published rate-card examples, not confirmed availability or final quotations. Taxes, validity and applicable terms are confirmed with the media owner.</p>
                </div>
            </div>
        </section>

        @if ($media->isNotEmpty())
            <section class="market-section" aria-labelledby="market-offers-title">
                <header class="market-heading"><div><h2 id="market-offers-title">Featured advertising opportunities.</h2><p>Browse available catalogue listings and source decks.</p></div><a href="{{ route('media.index') }}">Explore all media →</a></header>
                <div class="market-offers">
                    @foreach ($media->take(4) as $item)
                        <article class="market-offer"><span class="market-kicker">{{ $item->medium ?? 'Media' }}</span><h3><a href="{{ route('offers.show', $item->slug) }}">{{ $item->name }}</a></h3><p>{{ \Illuminate\Support\Str::limit((string) $item->summary, 120) }}</p><a class="market-link" href="{{ route('offers.show', $item->slug) }}">View opportunity →</a></article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="market-section" aria-labelledby="market-process-title">
            <header class="market-heading"><div><h2 id="market-process-title">From discovery to launch.</h2><p>Research, shortlist, plan and execute with the right media mix.</p></div><a href="{{ route('solutions') }}">Our connected solutions →</a></header>
            <div class="market-steps">
                @foreach ([['Discover', 'Find media and audiences.', 'search', 'media.index'], ['Compare', 'Review rates, formats and fit.', 'layers', 'rates.index'], ['Plan', 'Build a campaign around your brief.', 'calendar', 'advisor'], ['Launch', 'Request a proposal and execute.', 'megaphone', 'quote.builder']] as [$title, $copy, $icon, $route])
                    <a href="{{ route($route) }}"><span class="market-icon"><x-dm-icon :name="$icon" :size="27"/></span><div><strong>{{ $title }}</strong><p>{{ $copy }}</p></div><span aria-hidden="true">→</span></a>
                @endforeach
            </div>
        </section>

        <section class="market-onboard" aria-label="Join the media marketplace">
            <a href="{{ route('assets.join') }}"><x-dm-icon name="building" :size="30"/><div><strong>Media &amp; property owners</strong><p>Make your space discoverable to advertisers.</p></div><span>List your space →</span></a>
            <a href="{{ route('creators.join') }}"><x-dm-icon name="users" :size="30"/><div><strong>Creators &amp; talent</strong><p>Join our network and discover collaborations.</p></div><span>Join the network →</span></a>
        </section>

        <section class="market-cta" aria-labelledby="market-cta-title"><div class="market-cta__copy"><h2 id="market-cta-title">Tell us where your brand should go next.</h2><p>Start with your audience, objective and budget.</p></div><div><a class="button button--on-dark" href="{{ route('advisor') }}">Start planning →</a><a class="button button--outline-light" href="{{ route('contact') }}">Talk to the team</a></div></section>
    </div>
@endsection
