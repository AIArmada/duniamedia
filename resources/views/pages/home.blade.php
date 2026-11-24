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
            ['owner' => 'media-prima', 'channel' => 'hot-fm', 'label' => 'Radio · Personality-led campaigns', 'icon' => 'radio', 'accent' => '#d71938'],
            ['owner' => 'media-prima', 'channel' => 'tv3', 'label' => 'TV · News & entertainment', 'icon' => 'tv', 'accent' => '#3154ba'],
            ['owner' => 'media-prima', 'channel' => 'big-tree', 'label' => 'Outdoor · Landmark locations', 'icon' => 'billboard', 'accent' => '#178264'],
            ['owner' => 'karangkraf', 'channel' => 'sinar-harian', 'label' => 'Print · Digital news', 'icon' => 'print', 'accent' => '#bd202d'],
        ];
        $ownerSlugs = ['media-prima', 'astro', 'rtm', 'bernama', 'star-media-group', 'karangkraf', 'klia', 'al-hijrah'];
        $money = static fn ($price): string => 'RM '.number_format((float) $price, fmod((float) $price, 1.0) === 0.0 ? 0 : 2);
    @endphp

    <div class="market-home">
        <section class="market-hero" aria-labelledby="home-title">
            <div class="market-hero__copy">
                <span class="eyebrow">Malaysia’s media landscape, in one place</span>
                <h1 id="home-title">Discover, compare &amp;<br class="market-desktop-break"> plan media in Malaysia.</h1>
                <p>Find the right media owners, channels, personalities and advertising opportunities. Explore real campaigns, compare rates and build a plan with confidence.</p>
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
                        <a class="market-hero-brand" style="--brand-accent:{{ $brand['accent'] }}" href="{{ route('media.channel', [$brand['owner'], $brand['channel']]) }}">
                            <div class="market-hero-brand__visual">
                                <x-dm-icon :name="$brand['icon']" :size="76"/>
                                @if (! empty($channel['logo']))
                                    <img src="{{ $image::url('assets/partners/'.$channel['logo']) }}" alt="{{ $channel['name'] }}" width="180" height="80" decoding="async">
                                @else
                                    <strong>{{ $channel['name'] }}</strong>
                                @endif
                            </div>
                            <div class="market-hero-brand__body"><div><strong>{{ $channel['name'] }}</strong><span>{{ $brand['label'] }}</span></div><x-dm-icon name="arrow" :size="21"/></div>
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
            <header class="market-heading"><div><h2 id="market-intelligence-title">What actually sells attention.</h2><p>Discover the audiences, personalities, programmes and places behind the placements.</p></div><a href="{{ route('insights') }}">Media insights →</a></header>
            <div class="market-intelligence">
                <article class="market-intel market-intel--radio">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/partners/mp-hotfm-v2.png') }}" alt="Hot FM" width="150" height="65" loading="lazy"><x-dm-icon name="radio" :size="72"/></div>
                    <div class="market-intel__body"><span class="market-kicker">Radio audience</span><h3>Hot FM</h3><strong>{{ $hot['audience']['value'] }} weekly listeners</strong><p>{{ $hot['audience']['method'] }}</p><a href="{{ route('media.channel', ['media-prima', 'hot-fm']) }}">Explore Hot FM →</a><a class="market-source" href="{{ $hot['audience']['source'] }}" target="_blank" rel="noopener noreferrer">Source: Media Prima Audio ↗</a></div>
                </article>
                <article class="market-intel market-intel--programme">
                    <div class="market-intel__visual"><span>Bekpes<br><em>Hot</em></span><x-dm-icon name="users" :size="72"/></div>
                    <div class="market-intel__body"><span class="market-kicker">Programme &amp; personalities</span><h3>{{ $hot['programmes'][0]['title'] }}</h3><strong>{{ $hot['programmes'][0]['hosts'] }}</strong><p>Weekday breakfast · {{ $hot['programmes'][0]['time'] }}. Explore programme sponsorship and branded conversations.</p><a href="{{ route('media.channel', ['media-prima', 'hot-fm']) }}#hotfm-programme-bekpes-hot">Meet the presenters →</a></div>
                </article>
                <article class="market-intel market-intel--tv">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/partners/mp-tv3.png') }}" alt="TV3" width="150" height="65" loading="lazy"><x-dm-icon name="tv" :size="72"/></div>
                    <div class="market-intel__body"><span class="market-kicker">Television</span><h3>TV3</h3><strong>News, drama &amp; entertainment</strong><p>Explore television spots, studio formats, programme integrations and sponsorships.</p><a href="{{ route('media.channel', ['media-prima', 'tv3']) }}">Explore TV3 →</a></div>
                </article>
                <article class="market-intel market-intel--outdoor">
                    <div class="market-intel__visual"><img src="{{ $image::url('assets/partners/mp-bigtree.png') }}" alt="Big Tree" width="150" height="65" loading="lazy"><x-dm-icon name="billboard" :size="72"/></div>
                    <div class="market-intel__body"><span class="market-kicker">Out-of-home</span><h3>Big Tree</h3><strong>Landmark advertising locations</strong><p>Discover outdoor formats and placement opportunities including KLCC and KL Sentral.</p><a href="{{ route('media.channel', ['media-prima', 'big-tree']) }}">Explore Big Tree →</a></div>
                </article>
            </div>
        </section>

        @if ($featuredWork->isNotEmpty() || $trustClients->isNotEmpty())
            <section class="market-section" aria-labelledby="market-work-title">
                <header class="market-heading"><div><h2 id="market-work-title">Proof from real campaigns.</h2><p>Selected client work from the RA Media archive.</p></div><a href="{{ route('work.index') }}">Explore our work →</a></header>
                @if ($featuredWork->isNotEmpty())
                    <div class="market-work-grid">
                        @foreach ($featuredWork->take(3) as $work)
                            <a class="market-work-card" href="{{ route('work.index', ['client' => $work['client']]) }}#archive">
                                <div class="market-work-card__visual">
                                    @if ($work['cover'])
                                        <img src="{{ $image::url($work['cover']) }}" alt="" loading="lazy" decoding="async">
                                    @elseif ($work['client_logo'])
                                        <img class="market-work-card__logo" src="{{ $image::url($work['client_logo']) }}" alt="" loading="lazy" decoding="async">
                                    @else
                                        <x-dm-icon name="camera" :size="56"/>
                                    @endif
                                </div>
                                <div class="market-work-card__body"><span class="market-kicker">{{ $work['channel'] ?? $work['medium'] }} @if ($work['year']) · {{ $work['year'] }} @endif</span><h3>{{ $work['title'] }}</h3><strong>{{ $work['client_name'] }}</strong><p>{{ \Illuminate\Support\Str::limit((string) $work['description'], 140) }}</p><span class="market-link">View archive →</span></div>
                            </a>
                        @endforeach
                    </div>
                @endif
                @if ($trustClients->isNotEmpty())
                    <div class="market-client-strip"><strong>Selected clients from our archive</strong><div>
                        @foreach ($trustClients as $client)
                            <a href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="Work for {{ $client['name'] }}"><img src="{{ $image::url($client['logo']) }}" alt="{{ $client['name'] }}" width="100" height="48" loading="lazy"></a>
                        @endforeach
                    </div></div>
                @endif
            </section>
        @endif

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

        <section class="market-academy" aria-labelledby="market-academy-title">
            <div class="market-academy__art" aria-hidden="true"><span><x-dm-icon name="spark" :size="44"/></span><span><x-dm-icon name="chart" :size="44"/></span><span><x-dm-icon name="camera" :size="44"/></span><span><x-dm-icon name="code" :size="44"/></span><strong>DuniaMedia<br>Academy</strong></div>
            <div><span class="eyebrow">Learn by doing</span><h2 id="market-academy-title">DuniaMedia Academy</h2><p>Practical AI, media planning, content and marketing workshops for teams and professionals.</p><a class="market-primary" href="{{ route('academy') }}">Explore training →</a><a class="market-link" href="{{ route('services') }}">Creative &amp; technology services →</a></div>
        </section>

        <section class="market-onboard" aria-label="Join the media ecosystem">
            <a href="{{ route('assets.join') }}"><x-dm-icon name="building" :size="30"/><div><strong>Media &amp; property owners</strong><p>Make your space discoverable to advertisers.</p></div><span>List your space →</span></a>
            <a href="{{ route('creators.join') }}"><x-dm-icon name="users" :size="30"/><div><strong>Creators &amp; talent</strong><p>Join our network and discover collaborations.</p></div><span>Join the network →</span></a>
        </section>

        <section class="market-cta" aria-labelledby="market-cta-title"><div><h2 id="market-cta-title">Tell us where your brand should go next.</h2><p>Start with your audience, objective and budget.</p></div><div><a class="button button--on-dark" href="{{ route('advisor') }}">Start planning →</a><a class="button button--outline-light" href="{{ route('contact') }}">Talk to the team</a></div></section>
    </div>
@endsection
