@extends('layouts.app')

@section('content')
    @php($aboutClientGroups = require resource_path('data/about-clients.php'))

    <section class="about-hero" aria-labelledby="about-title">
        <div class="container about-hero__grid">
            <div class="about-hero__copy">
                <span class="eyebrow">Our story · Made in Malaysia</span>
                <h1 id="about-title">Every great story deserves to <em class="editorial">be heard.</em></h1>
                <p class="about-hero__lede">Behind every business is someone who believed in an idea. Behind every campaign is something worth saying. We exist to help those stories reach the people who need to hear them.</p>
                <p class="about-hero__identity">Dunia Media is a digital initiative by RA Media Relations — a Malaysian media and creative company with roots in 2008.</p>
                <div class="home-hero__actions about-actions">
                    <a href="#our-story" class="button button--primary">Discover our story <span aria-hidden="true">↓</span></a>
                    <a href="{{ route('contact') }}" class="button button--secondary">Meet your media partner <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <figure class="about-hero__media">
                <picture>
                    <source media="(max-width: 760px)" srcset="{{ asset('assets/img/ra-about-hero-mobile.webp') }}">
                    <img src="{{ asset('assets/img/ra-about-hero.webp') }}" alt="A film production crew working behind the camera" width="1672" height="941" fetchpriority="high" decoding="async">
                </picture>
                <figcaption><span>Behind the scenes</span><small>Illustrative production scene</small></figcaption>
            </figure>
        </div>
        <div class="container about-proof" aria-label="Our heritage and experience">
            <div><strong>2008</strong><span>Where our story began</span></div>
            <div><strong>2011</strong><span>RA Media officially incorporated</span></div>
            <div><strong>45</strong><span>Organisations in our company profile</span></div>
            <a class="text-link" href="#experience">Discover our experience <span aria-hidden="true">↘</span></a>
        </div>
    </section>

    <section class="section" id="our-story" aria-labelledby="story-title">
        <div class="container about-story">
            <div class="about-story__intro">
                <span class="eyebrow">How it all began</span>
                <h2 class="section-title" id="story-title">Before there was Dunia Media, there was <em class="editorial">Rumah Acara.</em></h2>
                <p class="about-story__opening">A simple idea in 2008: a home for creativity, events and stories.</p>
                <p>We began with video production. Working behind the camera taught us something that still guides us today: a story is only as powerful as its ability to reach someone.</p>
                <p>That idea led us beyond production into advertising, media planning and campaign consultancy. On 15 June 2011, RA Media Relations Sdn Bhd was incorporated in Taman Tun Dr Ismail, Kuala Lumpur.</p>
                <p>Today, our work spans businesses, public institutions and corporate teams. The ambition remains the same: to help people communicate something that matters.</p>
            </div>
            <div class="about-story__journey">
                <figure class="about-story__photo">
                    <img src="{{ asset('assets/img/ra-about-event.webp') }}" alt="A camera crew capturing a live event" loading="lazy" decoding="async">
                    <figcaption>Illustrative event production scene</figcaption>
                </figure>
                <div class="timeline" data-timeline aria-label="Our journey">
                    <span class="timeline__line" data-timeline-line aria-hidden="true"></span>
                    <article>
                        <time datetime="2008">2008</time>
                        <h3>It started with a story.</h3>
                        <p>A creative agency with roots in video production and an idea inspired by Rumah Acara.</p>
                    </article>
                    <article>
                        <time datetime="2011-06-15">15 June 2011</time>
                        <h3>We built a home for bigger ideas.</h3>
                        <p>RA Media Relations became an incorporated company in Kuala Lumpur.</p>
                    </article>
                    <article>
                        <span class="timeline__present">Today</span>
                        <h3>A whole world of media opens up.</h3>
                        <p>Dunia Media brings discovery, planning, creative services and new opportunities together.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="about-belief" aria-labelledby="belief-title">
        <div class="container about-belief__inner">
            <span class="eyebrow eyebrow--pale">What we believe</span>
            <h2 id="belief-title">Being seen is one thing.<br>Being <em class="editorial">remembered</em> is another.</h2>
            <p>Attention is valuable, but it is what happens next that matters. A business discovered. A public message understood. A community inspired. We believe good media should make a difference beyond the moment it appears.</p>
        </div>
    </section>

    <section class="section section--cream" id="capabilities" aria-labelledby="capabilities-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">From idea to impact</span>
                    <h2 class="section-title" id="capabilities-title">The right story.<br>The right place. <em class="editorial">The right people.</em></h2>
                    <p>We bring media planning, creative production and technology together so you can spend less time working out where to start, and more time making your message count.</p>
                </div>
            </div>
            <div class="capability-grid">
                <article class="capability-card">
                    <img src="{{ asset('assets/img/v2/service-planning.webp') }}" alt="" loading="lazy" decoding="async">
                    <div class="capability-card__body">
                        <span class="capability-card__label">Find your audience</span>
                        <h3>Make your message impossible to miss.</h3>
                        <p>Discover the right mix of television, radio, outdoor, indoor, cinema, press, in-flight and digital media for your campaign — guided by your goals and audience.</p>
                        <a class="text-link" href="{{ route('media.index') }}">Explore media <span aria-hidden="true">↗</span></a>
                    </div>
                </article>
                <article class="capability-card">
                    <img src="{{ asset('assets/img/v2/service-creative.webp') }}" alt="" loading="lazy" decoding="async">
                    <div class="capability-card__body">
                        <span class="capability-card__label">Give your idea a voice</span>
                        <h3>Make something people will remember.</h3>
                        <p>From commercials, corporate films and documentaries to photography, animation, design, copywriting, voice-over and multilingual translation.</p>
                        <a class="text-link" href="{{ route('work.index') }}">See our work <span aria-hidden="true">↗</span></a>
                    </div>
                </article>
                <article class="capability-card">
                    <img src="{{ asset('assets/img/v2/service-ai.webp') }}" alt="" loading="lazy" decoding="async">
                    <div class="capability-card__body">
                        <span class="capability-card__label">Open new possibilities</span>
                        <h3>Make tomorrow work for you today.</h3>
                        <p>Connect events, creators, multimedia and software with emerging AI capabilities and training — putting technology to work for people and businesses.</p>
                        <a class="text-link" href="{{ route('academy') }}">Explore training <span aria-hidden="true">↗</span></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="experience" aria-labelledby="experience-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Experience that speaks for itself</span>
                    <h2 class="section-title" id="experience-title">Different ambitions.<br>One shared <em class="editorial">trust.</em></h2>
                    <p>From national institutions to ambitious Malaysian businesses, every organisation represents a different audience, challenge and story. These are the clients named in RA Media's company profile.</p>
                </div>
                <div class="section-heading__aside page-hero__stats">
                    <div class="page-hero__stat"><strong>20</strong><span>video production</span></div>
                    <div class="page-hero__stat"><strong>28</strong><span>advertising</span></div>
                    <div class="page-hero__stat"><strong>45</strong><span>distinct organisations</span></div>
                </div>
            </div>
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
                                            <span>{{ $client['mark'] ?? $client['name'] }}</span>
                                        @endif
                                    </div>
                                    <h4>{{ $client['name'] }}</h4>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
            <div class="about-archive">
                <p class="muted small">Client names are drawn from RA Media's company profile. Campaign records, dates and platforms can be explored in our work archive; client contact details are withheld.</p>
                <a class="button button--secondary" href="{{ route('work.index') }}">Explore the campaign archive <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>

    <section class="section section--cream" aria-labelledby="future-title">
        <div class="container about-future">
            <figure class="about-future__media">
                <img src="{{ asset('assets/img/v2/hero-city.webp') }}" alt="An urban street with pedestrians and large outdoor advertising screens" width="1536" height="1024" loading="lazy" decoding="async">
                <figcaption>Illustrative view of the connected media landscape</figcaption>
            </figure>
            <div class="about-future__copy">
                <span class="eyebrow">The next chapter · Dunia Media</span>
                <h2 class="section-title" id="future-title">A world of media should be open to <em class="editorial">everyone.</em></h2>
                <p>Media can be difficult to navigate. Too many options. Too much jargon. Too little clarity about where your money should go.</p>
                <p>We built Dunia Media to make that world easier to explore. A place for brands to discover opportunities, for media owners to connect with advertisers, for creators to find collaborations, and for businesses to learn what technology can do for them.</p>
                <p>Whether you're launching your first business or planning your next national campaign, your story deserves a place in the world.</p>
                <a class="text-link" href="{{ route('advisor') }}">Find the right starting point <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>

    <section class="about-invitation" aria-labelledby="invitation-title">
        <div class="container about-invitation__inner">
            <span class="eyebrow eyebrow--pale">Your story starts here</span>
            <h2 id="invitation-title">You've got something worth sharing.<br><em class="editorial">Let's get it out into the world.</em></h2>
            <p>Bring us your ambition, your challenge, or just an idea. You don't need to have everything figured out before we talk.</p>
            <div class="about-actions">
                <a href="{{ route('contact') }}" class="button button--primary">Start a conversation <span aria-hidden="true">↗</span></a>
                <a href="{{ route('work.index') }}" class="about-invitation__link">Get inspired by our work <span aria-hidden="true">↗</span></a>
            </div>
        </div>
    </section>

    <section class="section section--tight" id="company-information" aria-labelledby="company-title">
        <div class="container">
            <div class="about-company-heading">
                <img src="{{ asset('assets/img/ra-media-relations-official.png') }}" alt="RA Media Relations" loading="lazy" decoding="async">
                <div>
                    <span class="eyebrow">The company behind Dunia Media</span>
                    <h2 class="section-title" id="company-title">Real people. A real company.</h2>
                    <p class="muted">Based in Kuala Lumpur, Malaysia.</p>
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
@endsection
