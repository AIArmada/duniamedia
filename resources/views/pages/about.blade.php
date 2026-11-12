@extends('layouts.app')

@section('content')
    @php($aboutClientGroups = require resource_path('data/about-clients.php'))

    <section class="about-hero">
        <div class="container about-hero__grid">
            <div>
                <span class="eyebrow">RA Media Relations · Kuala Lumpur</span>
                <h1>From Rumah Acara<br>to a <span class="editorial">media house.</span></h1>
                <p class="about-hero__lede">What began as a creative idea in 2008 grew into a media house bringing advertising, media planning and content production together — so good stories can find the right people, wherever they are.</p>
                <div class="home-hero__actions" style="margin-top:1.5rem">
                    <a href="#capabilities" class="button button--primary">Explore what we do <span aria-hidden="true">↓</span></a>
                    <a href="{{ route('contact') }}" class="button button--secondary">Talk to our team</a>
                </div>
                <div class="about-hero__milestones">
                    <div class="milestone">
                        <span>The idea</span>
                        <strong>2008</strong>
                        <p>Creative work inspired by “Rumah Acara” — a house for events and stories.</p>
                    </div>
                    <div class="milestone">
                        <span>Officially incorporated</span>
                        <strong>15 June 2011</strong>
                        <p>RA Media Relations Sdn Bhd, Taman Tun Dr Ismail.</p>
                    </div>
                </div>
            </div>
            <figure class="about-hero__media">
                <picture>
                    <source media="(max-width: 760px)" srcset="{{ asset('assets/img/ra-about-hero-mobile.webp') }}">
                    <img src="{{ asset('assets/img/ra-about-hero.webp') }}" alt="RA Media production team at work" width="1672" height="941" fetchpriority="high" decoding="async">
                </picture>
            </figure>
        </div>
    </section>

    <section class="section">
        <div class="container about-story">
            <div class="about-story__intro">
                <span class="eyebrow">Our story</span>
                <h2 class="section-title">A creative beginning. A wider media world.</h2>
                <p>RA began with video production, then stepped into advertising. Media planning, campaign advice and creative content followed — connecting strategy and making, rather than treating them as separate jobs.</p>
                <p>Today, the company works with SMEs, corporate teams and government agencies, helping shape marketing and branding activity for local and international audiences.</p>
                <p>The Dunia Media platform is the front-facing expression of that work: a clearer way to discover media, understand the options and start a proper conversation.</p>
            </div>
            <div class="timeline" data-timeline aria-label="How the company grew">
                <span class="timeline__line" data-timeline-line aria-hidden="true"></span>
                <article>
                    <time datetime="2008">2008</time>
                    <h3>Make the story</h3>
                    <p>The idea took shape as a creative agency focused on video production, with a name inspired by “Rumah Acara”.</p>
                </article>
                <article>
                    <time datetime="2011-06-15">15 June 2011</time>
                    <h3>Build the company</h3>
                    <p>RA Media Relations was registered as a private limited company in Taman Tun Dr Ismail, Kuala Lumpur.</p>
                </article>
                <article>
                    <time>Today</time>
                    <h3>Connect the work</h3>
                    <p>Planning, advertising and content now move across television, radio, print, outdoor, cinema and digital media.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="mission-strip">
        <div class="container mission-strip__grid">
            <div>
                <span class="eyebrow eyebrow--pale">Our mission</span>
                <h2>Make every opportunity a chance to do <em class="editorial">excellent work.</em></h2>
            </div>
            <div>
                <span class="eyebrow eyebrow--pale">Our vision</span>
                <p>Keep improving, serve clients better and grow together — as a true partner invested in the result, not just a creative resource for a single brief.</p>
            </div>
        </div>
    </section>

    <section class="section" id="capabilities">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">What we do</span>
                    <h2 class="section-title">Plan well. Make it memorable.</h2>
                    <p>RA Media Relations is an advertising media planner and creative content provider. The team can help at the planning table, behind the camera, in the edit, or wherever a campaign needs to meet its audience.</p>
                </div>
            </div>
            <div class="capability-grid">
                <article class="capability-card">
                    <span class="capability-card__label">Strategy &amp; placement</span>
                    <h3>Plan &amp; advise</h3>
                    <p>Media planning and advertising consultancy for SMEs, corporate clients and government agencies — with support for local and international marketing and branding activity.</p>
                    <ul>
                        <li>Television, radio and digital</li>
                        <li>Outdoor, indoor, cinema and press</li>
                        <li>In-flight magazines and media planning</li>
                    </ul>
                </article>
                <article class="capability-card">
                    <span class="capability-card__label">Production &amp; content</span>
                    <h3>Make the story</h3>
                    <p>Build stories for the screen, the page and digital platforms — from the first idea through to the finished piece.</p>
                    <ul>
                        <li>TV commercials, corporate films and documentaries</li>
                        <li>Animation, copywriting and design</li>
                        <li>Underwater videography and photography</li>
                    </ul>
                </article>
                <article class="capability-card">
                    <span class="capability-card__label">Events &amp; technology</span>
                    <h3>Bring it to life</h3>
                    <p>Connect people and platforms with the practical support a campaign or event needs.</p>
                    <ul>
                        <li>Event organisation and video conferences</li>
                        <li>Hosts, voice-over and translation in Jawi, Arabic, English and Mandarin</li>
                        <li>System development and multimedia technology</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="fieldwork">
        <div class="container">
            <div class="fieldwork__grid">
                <div class="fieldwork__media">
                    <figure><img src="{{ asset('assets/img/ra-about-underwater.webp') }}" alt="Illustrative underwater videographer filming a tropical reef" loading="lazy" decoding="async"></figure>
                    <figure><img src="{{ asset('assets/img/ra-about-event.webp') }}" alt="Illustrative event being filmed by a production crew" loading="lazy" decoding="async"></figure>
                </div>
                <div class="fieldwork__copy">
                    <span class="eyebrow eyebrow--pale">Storytelling in every medium</span>
                    <h2 class="section-title">Some stories happen on a stage. Others <em class="editorial">beneath the surface.</em></h2>
                    <p>For RA Media, video and photography are ways to bring stories closer. A campaign may call for a documentary, animation, corporate film or TV commercial. Another may need event coverage, a host, voice-over, or a camera that can work underwater.</p>
                    <p>The medium changes. The care behind the story stays the same.</p>
                    <a href="{{ route('contact') }}" class="text-link">Tell us what you have in mind <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--cream" id="experience">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Experience &amp; work</span>
                    <h2 class="section-title">Trusted across different worlds.</h2>
                    <p>From public institutions and national agencies to growing consumer brands, these are the organisations named in RA Media's company profile.</p>
                </div>
                <div class="section-heading__aside page-hero__stats">
                    <div class="page-hero__stat"><strong>20</strong><span>video production</span></div>
                    <div class="page-hero__stat"><strong>28</strong><span>advertising</span></div>
                    <div class="page-hero__stat"><strong>45</strong><span>distinct names</span></div>
                </div>
            </div>

            <div class="client-wall">
                @foreach ($aboutClientGroups as $group)
                    <section class="client-group" aria-label="{{ $group['eyebrow'] }} clients">
                        <header class="client-group__head">
                            <h3>{{ $group['title'] }}</h3>
                            <p>{{ $group['intro'] }}</p>
                            <span class="tag">{{ str_pad($group['count'], 2, '0', STR_PAD_LEFT) }} listed</span>
                        </header>
                        <div class="client-grid">
                            @foreach ($group['clients'] as $client)
                                <article class="client-tile">
                                    <div class="client-tile__mark">
                                        @if (! empty($client['logo']))
                                            <img src="{{ asset($client['logo']) }}" alt="" loading="lazy">
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

            <div class="rule"></div>
            <div class="work-note__inner" style="border:0;padding:0;background:none">
                <p class="muted small">Campaign records, dates and platforms are drawn from RA Media's client archive. Contact details are withheld.</p>
                <a class="text-link" href="{{ route('work.index') }}">Explore the campaign archive <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="section" id="company-information">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Company information</span>
                    <h2 class="section-title">Let's make the next story matter.</h2>
                </div>
                <div class="section-heading__aside">
                    <a href="{{ route('contact') }}" class="button button--primary">Start a conversation <span aria-hidden="true">↗</span></a>
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
