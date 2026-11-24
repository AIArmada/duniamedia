@extends('layouts.app')

@section('content')
    @php($aboutClientGroups = require resource_path('data/about-clients.php'))

    <div class="about-page">
        <section class="about-hero" aria-labelledby="about-title">
            <figure class="about-hero__media">
                <img src="{{ asset('assets/img/about-story-hero.jpg') }}" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
            </figure>
            <div class="container about-hero__grid">
                <div class="about-hero__copy">
                    <span class="about-kicker">Our story · Made in Malaysia</span>
                    <h1 id="about-title">Every great story deserves to <em>be heard.</em></h1>
                    <p>Behind every business is someone who believed in an idea. Behind every campaign is something worth saying. We help those stories find their audience.</p>
                    <div class="about-actions">
                        <a href="#our-story" class="about-button about-button--gold">Discover our story <span aria-hidden="true">↓</span></a>
                        <a href="{{ route('contact') }}" class="about-link about-link--light">Let's talk <span aria-hidden="true">↗</span></a>
                    </div>
                </div>
                <span class="about-hero__caption">The art of telling a story · Concept imagery</span>
            </div>
        </section>

        <section class="about-proof" aria-label="Our heritage and experience">
            <div class="container about-proof__inner">
                <div><strong>2008</strong><span>The idea began.<br>A home for creative stories.</span></div>
                <div><strong>2011</strong><span>RA Media Relations<br>officially incorporated.</span></div>
                <div><strong>45</strong><span>Distinct organisations<br>in our company profile.</span></div>
            </div>
        </section>

        <section class="about-section" id="our-story" aria-labelledby="story-title">
            <div class="container">
                <div class="about-story">
                    <div class="about-story__intro">
                        <span class="about-kicker">Chapter one · The beginning</span>
                        <h2 id="story-title">Before Dunia Media, there was <em>Rumah Acara.</em></h2>
                        <p class="about-story__opening">Every journey begins with a belief.</p>
                        <p>Ours took shape in 2008, with a simple idea: a home for creativity, events and stories. We started with video production, working behind the camera to bring ideas to life.</p>
                        <p>What we learned then still guides us today. A powerful story needs more than a beautiful image. It needs to reach someone.</p>
                        <p>That ambition led us into advertising, media planning and campaign consultancy. On 15 June 2011, RA Media Relations Sdn Bhd was incorporated in Taman Tun Dr Ismail, Kuala Lumpur.</p>
                    </div>
                    <figure class="about-story__photo">
                        <img src="{{ asset('assets/img/about-story-live.jpg') }}" alt="Illustration of a broadcast crew capturing a live event" width="1280" height="720" loading="lazy" decoding="async">
                        <figcaption>Every moment has a story. · Concept imagery</figcaption>
                    </figure>
                </div>
                <div class="about-timeline" aria-label="Our journey">
                    <article>
                        <time datetime="2008">2008</time>
                        <h3>A creative beginning.</h3>
                        <p>An idea inspired by Rumah Acara, with roots in video production.</p>
                    </article>
                    <article>
                        <time datetime="2011-06-15">2011</time>
                        <h3>A bigger stage.</h3>
                        <p>RA Media Relations became an incorporated company in Kuala Lumpur.</p>
                    </article>
                    <article>
                        <span class="about-timeline__today">Today</span>
                        <h3>A world of possibilities.</h3>
                        <p>Dunia Media connects media discovery, creative production and technology.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-belief" aria-labelledby="belief-title">
            <div class="container about-belief__inner">
                <div>
                    <span class="about-kicker">Chapter two · What drives us</span>
                    <h2 id="belief-title">Being seen is one thing.<br>Being <em>remembered</em> is another.</h2>
                </div>
                <div>
                    <p class="about-belief__statement">A business discovered. A public message understood. A community inspired.</p>
                    <p>Attention is valuable, but it's what happens next that matters. Our commitment is to keep improving, do excellent work and grow alongside the people who trust us with their stories.</p>
                </div>
            </div>
        </section>

        <section class="about-section about-section--sand" id="capabilities" aria-labelledby="capabilities-title">
            <div class="container">
                <div class="about-heading">
                    <div>
                        <span class="about-kicker">Chapter three · Our craft</span>
                        <h2 id="capabilities-title">Turning ideas into <em>real impact.</em></h2>
                    </div>
                    <p>Strategy, creativity and technology belong together. We bring them into one conversation, so you can focus on what you want to achieve.</p>
                </div>
                <div class="about-capabilities">
                    <article class="about-capability">
                        <a class="about-capability__image" href="{{ route('media.index') }}" tabindex="-1" aria-hidden="true">
                            <img src="{{ asset('assets/img/v2/service-planning.webp') }}" alt="" loading="lazy" decoding="async">
                        </a>
                        <div class="about-capability__body">
                            <span class="about-capability__number">01 / Find your audience</span>
                            <h3>Media planning &amp; placement</h3>
                            <p>Television, radio, outdoor, indoor, cinema, press, in-flight and digital. Find the right places to reach the right people.</p>
                            <a class="about-link" href="{{ route('media.index') }}">Explore media <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                    <article class="about-capability">
                        <a class="about-capability__image" href="{{ route('work.index') }}" tabindex="-1" aria-hidden="true">
                            <img src="{{ asset('assets/img/v2/service-creative.webp') }}" alt="" loading="lazy" decoding="async">
                        </a>
                        <div class="about-capability__body">
                            <span class="about-capability__number">02 / Give your idea a voice</span>
                            <h3>Creative production</h3>
                            <p>Commercials, corporate films, documentaries, animation, design, photography and underwater videography, copywriting, voice-over and multilingual translation.</p>
                            <a class="about-link" href="{{ route('work.index') }}">See our work <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                    <article class="about-capability">
                        <a class="about-capability__image" href="{{ route('academy') }}" tabindex="-1" aria-hidden="true">
                            <img src="{{ asset('assets/img/v2/service-ai.webp') }}" alt="" loading="lazy" decoding="async">
                        </a>
                        <div class="about-capability__body">
                            <span class="about-capability__number">03 / Open new possibilities</span>
                            <h3>Technology &amp; new media</h3>
                            <p>Events, hosts, video conferencing, creators, software, multimedia, AI and training — making emerging capabilities useful for people and businesses.</p>
                            <a class="about-link" href="{{ route('academy') }}">Explore training <span aria-hidden="true">↗</span></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-section about-experience" id="experience" aria-labelledby="experience-title">
            <div class="container">
                <div class="about-heading">
                    <div>
                        <span class="about-kicker">Chapter four · The people we've worked with</span>
                        <h2 id="experience-title">Different ambitions.<br>One shared <em>trust.</em></h2>
                    </div>
                    <p>From national institutions and public agencies to Malaysian businesses, we're proud to have contributed to different stories. Here are the organisations named in RA Media's company profile.</p>
                </div>
                <div class="about-experience__numbers" aria-label="Client roster by service">
                    <div><strong>20</strong><span>Video production clients</span></div>
                    <div><strong>28</strong><span>Advertising clients</span></div>
                    <div><strong>45</strong><span>Distinct organisations</span></div>
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
                <div class="about-archive">
                    <p>Client names are drawn from RA Media's company profile. Explore the campaign archive for documented work, dates and media placements.</p>
                    <a class="about-button about-button--outline" href="{{ route('work.index') }}">Explore the campaign archive <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </section>

        <section class="about-future" aria-labelledby="future-title">
            <figure class="about-future__media">
                <img src="{{ asset('assets/img/about-story-city.jpg') }}" alt="Illustrative Kuala Lumpur cityscape with pedestrians, media professionals and outdoor screens" width="1600" height="900" loading="lazy" decoding="async">
                <figcaption>Kuala Lumpur · Concept imagery</figcaption>
            </figure>
            <div class="about-future__copy">
                <span class="about-kicker">Chapter five · The next chapter</span>
                <h2 id="future-title">A world of media should be open to <em>everyone.</em></h2>
                <p>Media can be difficult to navigate. Too many options. Too much jargon. Too little clarity about where your money should go.</p>
                <p>We built Dunia Media to make that world easier to explore — for brands, media owners, creators and businesses looking to make technology work for them.</p>
                <p>Whether you're launching your first business or planning your next national campaign, your story deserves a place in the world.</p>
                <a class="about-link" href="{{ route('advisor') }}">Find your starting point <span aria-hidden="true">↗</span></a>
            </div>
        </section>

        <section class="about-invitation" aria-labelledby="invitation-title">
            <div class="container about-invitation__inner">
                <div>
                    <span class="about-kicker">The next story is yours</span>
                    <h2 id="invitation-title">You've got something worth sharing.<br><em>Let's get it out into the world.</em></h2>
                </div>
                <div>
                    <p>Bring us your ambition, your challenge, or just an idea. You don't need to have everything figured out before we talk.</p>
                    <a href="{{ route('contact') }}" class="about-button about-button--gold">Start a conversation <span aria-hidden="true">↗</span></a>
                </div>
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
