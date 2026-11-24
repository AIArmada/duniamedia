@extends('layouts.app')

@section('content')
    @php
        $aboutClientGroups = require resource_path('data/about-clients.php');
        $featuredClientLogos = collect($aboutClientGroups)
            ->pluck('clients')
            ->flatten(1)
            ->unique('name')
            ->filter(fn ($client) => isset($client['logo']) && empty($client['mark_style']))
            ->take(12);
        $capabilities = [
            ['title' => 'Video production', 'image' => 'assets/img/about-story-hero.jpg', 'icon' => 'tv', 'href' => route('work.index'), 'description' => 'Commercials, films, documentaries, animation, photography and voice-over.'],
            ['title' => 'Advertising & media buying', 'image' => 'assets/img/v2/medium-outdoor.webp', 'icon' => 'billboard', 'href' => route('media.index'), 'description' => 'Television, radio, outdoor, indoor, print, cinema and digital placements.'],
            ['title' => 'Events & experiential', 'image' => 'assets/img/about-story-live.jpg', 'icon' => 'creators', 'href' => route('services'), 'description' => 'Live production, hosts, multilingual translation and video conferencing.'],
            ['title' => 'Media solutions', 'image' => 'assets/img/v2/service-web.webp', 'icon' => 'digital', 'href' => route('solutions'), 'description' => 'Creative strategy, digital platforms, software, AI and training.'],
        ];
    @endphp

    <div class="about-page">
        <section class="about-hero" aria-labelledby="about-title">
            <figure class="about-hero__media">
                <img src="{{ asset('assets/img/about-story-city.jpg') }}" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
            </figure>
            <div class="container about-hero__grid">
                <div class="about-hero__copy">
                    <span class="about-kicker">Made in Malaysia. Connected to the world.</span>
                    <h1 id="about-title">Every great story<br>deserves to be <em>heard.</em></h1>
                    <p>We bring ideas to life, connect them with the right audiences and make them matter — across screens, spaces, platforms and communities.</p>
                    <a href="#our-story" class="about-story-link"><span class="about-story-link__circle" aria-hidden="true">↓</span> Discover our story</a>
                </div>
                <span class="about-hero__caption">Concept imagery · Kuala Lumpur</span>
            </div>
        </section>

        <section class="about-section" id="our-story" aria-labelledby="story-title">
            <div class="container about-story">
                <figure class="about-album">
                    <div class="about-album__print about-album__print--early">
                        <img src="{{ asset('assets/img/ra-about-event.webp') }}" alt="Illustration of an early film production setup" loading="lazy" decoding="async">
                        <span>Where it all began</span>
                    </div>
                    <div class="about-album__print about-album__print--today">
                        <img src="{{ asset('assets/img/about-story-live.jpg') }}" alt="Illustration of a contemporary live broadcast crew" loading="lazy" decoding="async">
                        <span>The world grows wider</span>
                    </div>
                    <figcaption>Illustrative scenes inspired by our journey, not archival photographs.</figcaption>
                </figure>
                <div class="about-story__intro">
                    <span class="about-kicker">Our journey</span>
                    <h2 id="story-title">From Rumah Acara<br>to <em>Dunia Media.</em></h2>
                    <p class="about-story__opening">It began with a simple belief: good stories can bring people together.</p>
                    <p>In 2008, an idea inspired by Rumah Acara — a home for events and creativity — took shape through video production. What began behind the camera grew into advertising, media planning and campaign consultancy.</p>
                    <p>RA Media Relations Sdn Bhd was incorporated on 15 June 2011 in Kuala Lumpur. Today, Dunia Media is its digital initiative, bringing that experience into a more connected world of media, creative work and technology.</p>
                </div>
                <div class="about-timeline" aria-label="Our journey through the years">
                    <article>
                        <time datetime="2008">2008</time>
                        <h3>The beginning</h3>
                        <p>A creative idea inspired by Rumah Acara.</p>
                    </article>
                    <article>
                        <time datetime="2011-06-15">2011</time>
                        <h3>A bigger stage</h3>
                        <p>RA Media Relations officially incorporated.</p>
                    </article>
                    <article>
                        <span class="about-timeline__today">Today</span>
                        <h3>A wider world</h3>
                        <p>Dunia Media brings the opportunities together.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-belief" aria-labelledby="belief-title">
            <figure class="about-belief__media" aria-hidden="true">
                <img src="{{ asset('assets/img/about-story-hero.jpg') }}" alt="" loading="lazy" decoding="async">
            </figure>
            <div class="container about-belief__inner">
                <div>
                    <h2 id="belief-title">Being seen is one thing.<br>Being <em>remembered</em> is another.</h2>
                    <span class="about-ink-stroke" aria-hidden="true"></span>
                </div>
                <div>
                    <p>Attention is valuable, but it's what happens next that matters. A business discovered. A public message understood. A community inspired.</p>
                    <p>That is the impact we work towards — growing alongside the people who trust us with their stories.</p>
                </div>
            </div>
        </section>

        <section class="about-section" id="capabilities" aria-labelledby="capabilities-title">
            <div class="container about-craft">
                <div class="about-craft__intro">
                    <span class="about-kicker">What we do</span>
                    <h2 id="capabilities-title">The <em>right</em> story.<br>The right place.<br>The right people.</h2>
                    <p>We combine creative production, strategic media and technology to help your message reach the audiences that matter.</p>
                    <a class="about-link" href="{{ route('services') }}">Explore all services <span aria-hidden="true">↗</span></a>
                </div>
                <div class="about-capabilities">
                    @foreach ($capabilities as $capability)
                        <a href="{{ $capability['href'] }}" class="about-capability">
                            <img src="{{ asset($capability['image']) }}" alt="" loading="lazy" decoding="async">
                            <div class="about-capability__body">
                                <x-dm-icon :name="$capability['icon']" :size="26"/>
                                <h3>{{ $capability['title'] }}</h3>
                                <p>{{ $capability['description'] }}</p>
                                <span aria-hidden="true">↗</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="about-section about-experience" id="experience" aria-labelledby="experience-title">
            <div class="container">
                <div class="about-experience__header">
                    <div>
                        <span class="about-kicker">The trust behind our work</span>
                        <h2 id="experience-title">Different ambitions.<br>One shared <em>trust.</em></h2>
                    </div>
                    <div class="about-experience__numbers" aria-label="Clients by service">
                        <div><strong>20</strong><span>Video production clients</span></div>
                        <div><strong>28</strong><span>Advertising clients</span></div>
                        <div><strong>45</strong><span>Distinct organisations</span></div>
                    </div>
                </div>
                <div class="about-featured-clients">
                    <div class="about-featured-clients__logos">
                        @foreach ($featuredClientLogos as $client)
                            <img src="{{ \App\Features\Catalog\Support\MediaImage::url($client['logo']) }}" alt="{{ $client['name'] }}" loading="lazy" decoding="async">
                        @endforeach
                    </div>
                    <div class="about-featured-clients__note">
                        <p>From public institutions to Malaysian businesses — different stories, one shared commitment to meaningful work.</p>
                        <a href="{{ route('work.index') }}" class="about-link">See our work <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
                <details class="about-client-disclosure">
                    <summary>Explore the complete client roster <span>45 organisations</span></summary>
                    <div class="client-wall">
                        @foreach ($aboutClientGroups as $group)
                            <section class="client-group" aria-label="{{ $group['eyebrow'] }} clients">
                                <header class="client-group__head">
                                    <div>
                                        <span class="eyebrow">{{ $group['eyebrow'] }}</span>
                                        <h3>{{ $group['title'] }}</h3>
                                    </div>
                                    <span class="muted small">{{ $group['count'] }} organisations</span>
                                </header>
                                <div class="client-grid">
                                    @foreach ($group['clients'] as $client)
                                        <article class="client-tile">
                                            <div @class(['client-tile__mark', $client['mark_style'] ?? ''])>
                                                @if (! empty($client['logo']))
                                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($client['logo']) }}" alt="" loading="lazy" decoding="async">
                                                @else
                                                    <span>{{ str_replace('\\n', "\n", $client['mark'] ?? $client['name']) }}</span>
                                                @endif
                                            </div>
                                            <h4>{{ $client['name'] }}</h4>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </details>
                <p class="about-client-source">Client names and service counts are drawn from RA Media's company profile. Explore our campaign archive for documented work and placements.</p>
            </div>
        </section>

        <section class="about-future" aria-labelledby="future-title">
            <figure class="about-future__media">
                <img src="{{ asset('assets/img/about-story-live.jpg') }}" alt="" width="1280" height="720" loading="lazy" decoding="async">
            </figure>
            <div class="container about-future__inner">
                <div class="about-future__copy">
                    <span class="about-kicker">Our purpose</span>
                    <h2 id="future-title">A world of media should be <em>open to everyone.</em></h2>
                    <p>We empower entrepreneurs, organisations, media owners and creators with the tools, talent and media opportunities to make a real difference — for their business, their audience and the communities they serve.</p>
                    <p>Whether you're launching your first business or planning your next national campaign, your story deserves a place in the world.</p>
                    <a href="{{ route('contact') }}" class="about-button">Let's create together <span aria-hidden="true">↗</span></a>
                    <a class="about-link about-link--light" href="{{ route('advisor') }}">Find your starting point <span aria-hidden="true">↗</span></a>
                </div>
                <span class="about-future__caption">Concept imagery</span>
            </div>
        </section>

        <section class="about-section about-section--sand" id="company-information" aria-labelledby="company-title">
            <div class="container">
                <div class="about-company-heading">
                    <img src="{{ asset('assets/img/ra-media-relations-official.png') }}" alt="RA Media Relations" loading="lazy" decoding="async">
                    <div>
                        <span class="about-kicker">The company behind Dunia Media</span>
                        <h2 id="company-title">Real people. A real company.</h2>
                        <p>Based in Kuala Lumpur, Malaysia.</p>
                    </div>
                </div>
                <div class="contact-facts">
                    <div class="contact-fact">
                        <span>Registered company</span>
                        <h3>RA Media Relations Sdn Bhd</h3>
                        <p>Company registration no. <strong>948761-W</strong></p>
                        <p>Incorporated 15 June 2011</p>
                    </div>
                    <div class="contact-fact">
                        <span>Corporate address</span>
                        <address>19B, Jalan Wan Kadir 2,<br>Taman Tun Dr Ismail,<br>60000 Kuala Lumpur,<br>Wilayah Persekutuan, Malaysia</address>
                        <p style="margin-top:0.5rem"><a href="https://www.google.com/maps/search/?api=1&amp;query=19B%2C+Jalan+Wan+Kadir+2%2C+Taman+Tun+Dr+Ismail%2C+60000+Kuala+Lumpur%2C+Wilayah+Persekutuan%2C+Malaysia" target="_blank" rel="noopener noreferrer">Open in Maps ↗</a></p>
                    </div>
                    <div class="contact-fact">
                        <span>Contact</span>
                        <h3>Ifawani Omar</h3>
                        <p><a href="tel:+60193894889">+60 19 389 4889</a><br><a href="mailto:ramediarelations@gmail.com">ramediarelations@gmail.com</a><br><a href="mailto:ifa@ramediaco.org">ifa@ramediaco.org</a></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
