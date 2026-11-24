@extends('layouts.app')

@section('content')
    @php
        $films = collect($media)->keyBy('key');
        $heroFilm = $films->get('jwp-psa-kl');
        $featuredFilm = $films->get('jwp-psa-putrajaya');
        $gallery = $films->except(['jwp-psa-kl', 'jwp-psa-putrajaya'])->values();
        $clientPriority = array_flip(['kpj-kajang', 'suruhanjaya-tenaga', 'yayasan-maghfirah', 'yayasan-pelajaran-mara', 'perkeso', 'todak']);
        $featuredClients = collect($clients)
            ->filter(fn ($client) => ! empty($client['logo']) && ($campaignCounts[$client['slug']] ?? 0) > 0)
            ->sortBy(fn ($client) => $clientPriority[$client['slug']] ?? 99)
            ->take(6);
    @endphp

    <div class="work-page">
        <section class="work-opening" aria-labelledby="work-title">
            <div class="container work-opening__inner">
                <div class="work-opening__copy">
                    <span class="work-kicker">Our work</span>
                    <h1 id="work-title">Work that speaks for <em>itself.</em></h1>
                    <p>Campaigns, content and media collaborations for Malaysian brands and public institutions — created to inform, engage and inspire.</p>
                    <a class="work-text-link" href="#archive">Explore our campaigns <span aria-hidden="true">↓</span></a>
                </div>
                @if ($heroFilm)
                    <x-work-film :item="$heroFilm" class="work-film--hero"/>
                @endif
            </div>
        </section>

        <section class="work-proof" aria-label="Our work in numbers">
            <div class="container work-proof__grid">
                @foreach ([
                    ['number' => $stats['campaigns'], 'label' => 'Campaign records', 'icon' => 'camera'],
                    ['number' => $stats['clients'], 'label' => 'Organisations', 'icon' => 'building'],
                    ['number' => $stats['mediums'], 'label' => 'Media categories', 'icon' => 'radio'],
                    ['number' => count($media), 'label' => 'Video & audio projects', 'icon' => 'play'],
                ] as $stat)
                    <div class="work-proof__item">
                        <x-dm-icon :name="$stat['icon']" :size="32"/>
                        <strong>{{ $stat['number'] }}</strong>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="work-section" id="work-media" aria-labelledby="work-media-title">
            <div class="container">
                <div class="work-feature">
                    <div class="work-feature__copy">
                        <span class="work-kicker">On screen &amp; on air</span>
                        <h2 id="work-media-title">Stories brought to life.</h2>
                        <p>From public service films to television interviews, commercials and voiceover work. Explore the productions and collaborations behind the campaigns.</p>
                        <a class="work-text-link" href="#work-film-gallery">Explore the collection <span aria-hidden="true">↓</span></a>
                    </div>
                    @if ($featuredFilm)
                        <x-work-film :item="$featuredFilm" class="work-film--feature"/>
                    @endif
                </div>

                <div class="work-film-gallery" id="work-film-gallery">
                    @foreach ($gallery as $item)
                        <x-work-film :item="$item"/>
                    @endforeach
                </div>
            </div>
        </section>

        @if (count($clients) > 0)
            <section class="work-clients" id="clients" aria-labelledby="work-clients-title">
                <div class="container">
                    <div class="work-clients__strip">
                        <div>
                            <span class="work-kicker">Our clients</span>
                            <h2 id="work-clients-title">In good company.</h2>
                        </div>
                        <div class="work-clients__logos">
                            @foreach ($featuredClients as $client)
                                <a href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="View campaigns for {{ $client['name'] }}">
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($client['logo']) }}" alt="{{ $client['name'] }}" loading="lazy" decoding="async">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <details class="work-client-disclosure">
                        <summary>Explore all {{ $stats['clients'] }} organisations</summary>
                        @foreach (['government' => 'Government & public institutions', 'business' => 'Business & brands'] as $sector => $label)
                            <section class="work-client-group" aria-label="{{ $label }}">
                                <header class="work-client-group__header">
                                    <span>{{ $label }}</span>
                                    <span>{{ count($clientsBySector[$sector] ?? []) }} organisations</span>
                                </header>
                                <div class="work-client-grid">
                                    @foreach ($clientsBySector[$sector] ?? [] as $client)
                                        @php($campaignCount = $campaignCounts[$client['slug']] ?? 0)
                                        @if ($campaignCount > 0)
                                            <a class="work-client-card" href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="View campaigns for {{ $client['name'] }}">
                                        @else
                                            <article class="work-client-card is-static">
                                        @endif
                                            <span class="work-client-card__mark @if (empty($client['logo'])) is-typeset @endif">
                                                @if (! empty($client['logo']))
                                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($client['logo']) }}" alt="" loading="lazy" decoding="async">
                                                @else
                                                    <span>{{ $client['mark'] ?? $client['short_name'] }}</span>
                                                @endif
                                            </span>
                                            <span class="work-client-card__meta">
                                                <h3>{{ $client['name'] }}</h3>
                                                <span>{{ $campaignCount > 0 ? $campaignCount.' '.($campaignCount === 1 ? 'campaign' : 'campaigns') : 'Client roster' }}</span>
                                            </span>
                                        @if ($campaignCount > 0)
                                            </a>
                                        @else
                                            </article>
                                        @endif
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </details>
                </div>
            </section>
        @endif

        <livewire:work-explorer/>

        <section class="work-invitation" aria-labelledby="work-invitation-title">
            <img class="work-invitation__image" src="{{ asset('assets/img/work-next-campaign.jpg') }}" srcset="{{ asset('assets/img/work-next-campaign-mobile.jpg') }} 760w, {{ asset('assets/img/work-next-campaign.jpg') }} 1648w" sizes="100vw" width="1648" height="928" alt="" loading="lazy" decoding="async">
            <div class="container work-invitation__inner">
                <div>
                    <span class="work-kicker">Your next story</span>
                    <h2 id="work-invitation-title">Let's make your next campaign <em>matter.</em></h2>
                    <p>Bring your idea, ambition or challenge. We'll help you find the right way forward.</p>
                    <div class="work-invitation__actions">
                        <a class="work-cta" href="{{ route('advisor') }}">Plan a campaign <span aria-hidden="true">↗</span></a>
                        <a class="work-text-link work-text-link--light" href="{{ route('contact') }}">Talk to our team <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
