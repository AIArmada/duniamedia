@extends('layouts.app')

@section('content')
    @php($aboutClientGroups = require resource_path('data/about-clients.php'))
    <section class="about-hero">
        <div class="about-hero__image" aria-hidden="true">
            <img src="{{ asset('assets/img/ra-about-hero.webp') }}" alt="">
        </div>
        <div class="about-hero__shade" aria-hidden="true"></div>

        <div class="container about-hero__content">
            <div class="about-hero__copy">
                <span class="eyebrow pale">RA MEDIA RELATIONS · KUALA LUMPUR</span>
                <h1>From Rumah Acara<br><em>to a media house.</em></h1>
                <p>What began as a creative idea in 2008 grew into a media house bringing advertising, media planning and content production together — so good stories can find the right people, wherever they are.</p>
                <div class="about-hero__actions">
                    <a href="#capabilities" class="btn btn-white">Explore what we do <span>↓</span></a>
                    <a href="{{ route('contact') }}" class="btn btn-ghost-light">Talk to our team</a>
                </div>
            </div>

            <figure class="about-hero__identity">
                <img src="{{ asset('assets/img/ra-media-relations-official.png') }}" alt="Official RA Media Relations logo">
                <figcaption>
                    <span>RA Media Relations Sdn Bhd</span>
                    <span>948761-W</span>
                </figcaption>
            </figure>

            <div class="about-hero__milestones" aria-label="Company history">
                <div class="about-hero__milestone">
                    <span>The idea</span>
                    <strong>2008</strong>
                    <p>Creative work inspired by “Rumah Acara” — a house for events and stories.</p>
                </div>
                <div class="about-hero__milestone">
                    <span>Officially incorporated</span>
                    <strong>15 June 2011</strong>
                    <p>RA Media Relations Sdn Bhd, Taman Tun Dr Ismail.</p>
                </div>
            </div>
        </div>

        <div class="about-hero__caption">Stories begin with an idea. The right media helps them travel.</div>
    </section>

    <section class="section about-origin">
        <div class="container about-origin__grid">
            <div class="about-origin__intro">
                <span class="eyebrow">OUR STORY</span>
                <h2>A creative beginning.<br><span>A wider media world.</span></h2>
                <p>RA began with video production, then stepped into advertising. Media planning, campaign advice and creative content followed — connecting strategy and making, rather than treating them as separate jobs.</p>
                <p>Today, the company works with SMEs, corporate teams and government agencies, helping shape marketing and branding activity for local and international audiences.</p>
            </div>

            <div class="about-origin__timeline" aria-label="How the company grew">
                <article class="about-origin__chapter">
                    <span class="about-origin__date">2008</span>
                    <div>
                        <h3>Make the story</h3>
                        <p>The idea took shape as a creative agency focused on video production, with a name inspired by “Rumah Acara.”</p>
                    </div>
                </article>
                <article class="about-origin__chapter">
                    <span class="about-origin__date">2011</span>
                    <div>
                        <h3>Build the company</h3>
                        <p>RA Media Relations was registered as a private limited company on 15 June in Taman Tun Dr Ismail, Kuala Lumpur.</p>
                    </div>
                </article>
                <article class="about-origin__chapter">
                    <span class="about-origin__date">Today</span>
                    <div>
                        <h3>Connect the work</h3>
                        <p>Planning, advertising and content now move across television, radio, print, outdoor, cinema and digital media.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="about-purpose">
        <div class="container about-purpose__grid">
            <div class="about-purpose__statement">
                <span class="eyebrow pale">OUR MISSION</span>
                <h2>Make every opportunity a chance to do <em>excellent work.</em></h2>
            </div>
            <div class="about-purpose__vision">
                <span class="about-purpose__rule" aria-hidden="true"></span>
                <span class="eyebrow pale">OUR VISION</span>
                <p>Keep improving, serve clients better and grow together — as a true partner invested in the result, not just a creative resource for a single brief.</p>
            </div>
        </div>
    </section>

    <section class="section about-capabilities" id="capabilities">
        <div class="container">
            <div class="about-capabilities__heading">
                <div>
                    <span class="eyebrow">WHAT WE DO</span>
                    <h2>Plan well.<br><span>Make it memorable.</span></h2>
                </div>
                <p>RA Media Relations is an advertising media planner and creative content provider. The team can help at the planning table, behind the camera, in the edit, or wherever a campaign needs to meet its audience.</p>
            </div>

            <div class="about-capabilities__grid">
                <article class="about-capability">
                    <span class="about-capability__label">Strategy &amp; placement</span>
                    <h3>Plan &amp; advise</h3>
                    <p>Media planning and advertising consultancy for SMEs, corporate clients and government agencies — with support for local and international marketing and branding activity.</p>
                    <ul>
                        <li>Television, radio and digital</li>
                        <li>Outdoor, indoor, cinema and press</li>
                        <li>In-flight magazines and media planning</li>
                    </ul>
                </article>

                <article class="about-capability">
                    <span class="about-capability__label">Production &amp; content</span>
                    <h3>Make the story</h3>
                    <p>Build stories for the screen, the page and digital platforms — from the first idea through to the finished piece.</p>
                    <ul>
                        <li>TV commercials, corporate films and documentaries</li>
                        <li>Animation, copywriting and design</li>
                        <li>Underwater videography and photography</li>
                    </ul>
                </article>

                <article class="about-capability">
                    <span class="about-capability__label">Events &amp; technology</span>
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

    <section class="about-fieldwork">
        <div class="container about-fieldwork__grid">
            <div class="about-fieldwork__visual">
                <figure class="about-fieldwork__underwater">
                    <img src="{{ asset('assets/img/ra-about-underwater.webp') }}" alt="Illustrative underwater videographer filming a tropical reef">
                    <figcaption><span>Underwater work</span><small>Videography · Photography</small></figcaption>
                </figure>
                <figure class="about-fieldwork__event">
                    <img src="{{ asset('assets/img/ra-about-event.webp') }}" alt="Illustrative event being filmed by a production crew">
                    <figcaption>Events &amp; live coverage</figcaption>
                </figure>
            </div>
            <div class="about-fieldwork__copy">
                <span class="eyebrow pale">STORYTELLING IN EVERY MEDIUM</span>
                <h2>Some stories happen on a stage. Others <em>beneath the surface.</em></h2>
                <p>For RA Media, video and photography are ways to bring stories closer. A campaign may call for a documentary, animation, corporate film or TV commercial. Another may need event coverage, a host, voice-over, or a camera that can work underwater.</p>
                <p>The medium changes. The care behind the story stays the same.</p>
                <a href="{{ route('contact') }}" class="text-link about-fieldwork__link">Tell us what you have in mind <span>↗</span></a>
            </div>
        </div>
    </section>

    <section class="section about-experience" id="experience">
        <div class="container">
            <div class="about-experience__heading">
                <div>
                    <span class="eyebrow">EXPERIENCE &amp; WORK</span>
                    <h2>Trusted across<br><span>different worlds.</span></h2>
                </div>
                <div class="about-experience__summary">
                    <p>From public institutions and national agencies to growing consumer brands, these are the organizations named in RA Media’s company profile.</p>
                    <div class="about-experience__counts" aria-label="48 client entries across two service areas">
                        <span><strong>20</strong><small>video production</small></span>
                        <span><strong>28</strong><small>advertising</small></span>
                        <span><strong>45</strong><small>distinct names</small></span>
                    </div>
                </div>
            </div>

            <div class="about-projects">
                <div class="about-projects__heading">
                    <span class="eyebrow">ON THE GROUND</span>
                    <p>Event appearances highlighted in the company profile.</p>
                </div>
                <article class="about-project">
                    <span>PUTRAJAYA · 2022</span>
                    <h3>TODAK Karnival Jom Heboh</h3>
                </article>
                <article class="about-project">
                    <span>SHAH ALAM · 1 JUNE 2023</span>
                    <h3>Vocket Booth, Karnival Jom Heboh</h3>
                </article>
            </div>

            <div class="about-client-wall">
                @foreach ($aboutClientGroups as $group)
                    <section class="about-client-group" aria-label="{{ $group['eyebrow'] }} clients">
                        <header class="about-client-group__header">
                            <div class="about-client-group__index" aria-hidden="true">{{ $group['number'] }}</div>
                            <div class="about-client-group__title">
                                <span class="eyebrow pale">{{ $group['eyebrow'] }}</span>
                                <h3>{{ $group['title'] }}</h3>
                            </div>
                            <p>{{ $group['intro'] }}</p>
                            <div class="about-client-group__count"><strong>{{ str_pad($group['count'], 2, '0', STR_PAD_LEFT) }}</strong><span>listed<br>organizations</span></div>
                        </header>

                        <div class="about-client-grid">
                            @foreach ($group['clients'] as $client)
                                <article class="about-client-card">
                                    <div class="about-client-card__mark {{ empty($client['logo']) ? 'is-typeset' : '' }} {{ $client['mark_style'] ?? '' }}">
                                        <span class="about-client-card__index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        @if (!empty($client['logo']))
                                            <img src="{{ asset($client['logo']) }}" alt="" loading="lazy">
                                        @else
                                            <span class="about-client-card__wordmark" aria-hidden="true">{!! nl2br(e(str_replace('\\n', "\n", $client['mark']))) !!}</span>
                                        @endif
                                    </div>
                                    <h4>{{ $client['name'] }}</h4>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="about-experience__archive">
                <p>Campaign records, dates and platforms are drawn from RA Media's client archive. Contact details are withheld.</p>
                <a href="{{ route('work.index') }}">Explore the campaign archive <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="section about-contact" id="company-information">
        <div class="container">
            <div class="about-contact__heading">
                <div>
                    <span class="eyebrow">COMPANY INFORMATION</span>
                    <h2>Let’s make the next story <span>matter.</span></h2>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-primary">Start a conversation <span>↗</span></a>
            </div>

            <div class="about-contact__grid">
                <div class="about-contact__block">
                    <span class="about-contact__label">Registered company</span>
                    <h3>RA Media Relations Sdn Bhd</h3>
                    <p>Company registration no. <strong>948761-W</strong></p>
                    <p>Incorporated 15 June 2011</p>
                </div>
                <div class="about-contact__block">
                    <span class="about-contact__label">Corporate address</span>
                    <address>19B, Jalan Wan Kadir 2,<br>Taman Tun Dr Ismail,<br>60000 Kuala Lumpur,<br>Wilayah Persekutuan, Malaysia</address>
                    <a class="about-contact__link" href="https://www.google.com/maps/search/?api=1&amp;query=19B%2C+Jalan+Wan+Kadir+2%2C+Taman+Tun+Dr+Ismail%2C+60000+Kuala+Lumpur%2C+Wilayah+Persekutuan%2C+Malaysia" target="_blank" rel="noopener noreferrer">Open in Maps <span>↗</span></a>
                </div>
                <div class="about-contact__block">
                    <span class="about-contact__label">Contact</span>
                    <p class="about-contact__person">Ifawani Omar</p>
                    <a href="tel:+60193894889">+60 19 389 4889</a>
                    <a href="mailto:ramediarelations@gmail.com">ramediarelations@gmail.com</a>
                    <a href="mailto:ifa@ramediaco.org">ifa@ramediaco.org</a>
                </div>
            </div>
        </div>
    </section>
@endsection
