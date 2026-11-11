@extends('layouts.app')

@section('content')
    @php
        $channels = [
            ['title' => 'Outdoor & DOOH', 'copy' => 'Billboards, LED and transit where your audience already moves.', 'image' => 'assets/img/v2/channel-outdoor.webp', 'type' => 'Outdoor', 'class' => 'anchor', 'icon' => 'billboard'],
            ['title' => 'TV & Broadcast', 'copy' => 'Free-to-air, pay-TV, news and studio formats that build mass reach.', 'image' => 'assets/img/v2/channel-tv.webp', 'type' => 'TV & Broadcast', 'class' => 'medium', 'icon' => 'tv'],
            ['title' => 'Radio & Audio', 'copy' => 'Drive-time, music and talk formats with loyal, local listeners.', 'image' => 'assets/img/v2/channel-radio.webp', 'type' => 'Radio', 'class' => 'medium', 'icon' => 'radio'],
            ['title' => 'Digital & Publishing', 'copy' => 'Display, video, editorial and social for precise, measurable reach.', 'image' => 'assets/img/v2/channel-digital.webp', 'type' => 'Digital', 'class' => 'small', 'icon' => 'digital'],
            ['title' => 'Print & Publishing', 'copy' => 'Newspapers, magazines and advertorials with lasting context.', 'image' => 'assets/img/v2/channel-print.webp', 'type' => 'Print', 'class' => 'small', 'icon' => 'print'],
            ['title' => 'Creators & Talent', 'copy' => 'Voices and communities that make a message feel native.', 'image' => 'assets/img/v2/channel-creators.webp', 'type' => 'Creators', 'class' => 'small', 'icon' => 'creators'],
            ['title' => 'Custom Spaces', 'copy' => 'Retail, in-premise and unexpected places turned into media.', 'image' => 'assets/img/v2/channel-spaces.webp', 'type' => 'Indoor', 'class' => 'small', 'icon' => 'spaces'],
        ];

        $heroFeature = $featuredWork->first();
        $supportWork = $featuredWork->slice(1, 3);

        $personas = [
            ['title' => 'Brands & advertisers', 'copy' => 'Find suitable media, then a team that can execute around it.', 'cta' => 'Plan a campaign', 'url' => route('advisor'), 'image' => 'assets/img/v2/persona-brand.webp'],
            ['title' => 'Media & property owners', 'copy' => 'Explore the advertising potential of the space you manage.', 'cta' => 'List your space', 'url' => route('assets.join'), 'image' => 'assets/img/v2/persona-owner.webp'],
            ['title' => 'Creators & talent', 'copy' => 'Access collaborations, production support and growth.', 'cta' => 'Join the network', 'url' => route('creators.join'), 'image' => 'assets/img/v2/persona-creator.webp'],
            ['title' => 'Business & training teams', 'copy' => 'Make AI and technology genuinely useful at work.', 'cta' => 'Explore training', 'url' => route('academy'), 'image' => 'assets/img/v2/persona-training.webp'],
        ];
    @endphp

    <section class="home-hero" aria-labelledby="home-title">
        <div class="container home-hero__inner">
            <div class="home-hero__copy">
                <span class="eyebrow">Media · Creative · Technology</span>
                <h1 id="home-title">Every channel.<br>One <span class="editorial">connected</span> campaign.</h1>
                <p class="home-hero__lede">From television and radio to outdoor media, digital platforms, creators and custom spaces, Dunia Media helps brands find the right places to be seen. We also connect the strategy, production and technology that turn attention into meaningful action.</p>
                <div class="home-hero__actions">
                    <a class="button button--primary" href="{{ route('advisor') }}">Plan your campaign <span aria-hidden="true">↗</span></a>
                    <a class="button button--secondary" href="{{ route('media.index') }}">Explore media</a>
                </div>
            </div>

            <div class="home-hero__art" data-hero-art aria-label="A connected media world">
                <svg class="hero-ribbon" data-hero-ribbon viewBox="0 0 520 300" fill="none" aria-hidden="true">
                    <path d="M14 244C90 176 126 78 236 62c86-13 156 32 210 104" stroke="#D3A071" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="5 7"/>
                </svg>
                <figure class="hero-shot hero-shot--main" data-hero-shot="main">
                    <picture>
                        <source media="(max-width: 760px)" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/hero-city-mobile-bg.webp', [480, 640, 960]) }}" sizes="100vw">
                        <img src="{{ \App\Support\MediaImage::url('assets/img/v2/hero-city.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/hero-city.webp', [640, 960, 1536]) }}" sizes="(max-width: 1024px) 100vw, 46vw" alt="Kuala Lumpur city street at dusk with a large digital billboard and pedestrians" width="1536" height="1024" fetchpriority="high" decoding="async">
                    </picture>
                    <figcaption>Outdoor · LED</figcaption>
                </figure>
                <figure class="hero-shot hero-shot--top" data-hero-shot="top">
                    <img src="{{ \App\Support\MediaImage::url('assets/img/v2/channel-tv.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/channel-tv.webp', [480, 960, 1280]) }}" sizes="(max-width: 760px) 42vw, 22vw" alt="Television studio with a presenter and camera crew" width="1536" height="1024" loading="lazy" decoding="async">
                    <figcaption>Broadcast</figcaption>
                </figure>
                <figure class="hero-shot hero-shot--bottom" data-hero-shot="bottom">
                    <img src="{{ \App\Support\MediaImage::url('assets/img/v2/channel-creators.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/channel-creators.webp', [480, 960, 1280]) }}" sizes="(max-width: 760px) 48vw, 24vw" alt="Creator filming a product story at home" width="1536" height="1024" loading="lazy" decoding="async">
                    <figcaption>Creators</figcaption>
                </figure>
            </div>

            <div class="home-hero__utility">
                <a href="{{ route('media.index') }}"><span aria-hidden="true">01</span> Find media</a>
                <a href="{{ route('work.index') }}"><span aria-hidden="true">02</span> See real campaigns</a>
                <a href="{{ route('contact') }}"><span aria-hidden="true">03</span> Talk to the team</a>
            </div>
        </div>
    </section>

    @if ($trustClients->isNotEmpty())
        <section class="trust-strip" aria-label="Selected clients">
            <div class="container trust-strip__inner">
                <div class="trust-strip__intro">
                    <strong>Trusted across sectors</strong>
                    <p>Government institutions, national agencies and Malaysian brands — drawn from the RA Media archive.</p>
                </div>
                <div class="trust-strip__logos">
                    @foreach ($trustClients as $client)
                        <a href="{{ route('work.index', ['client' => $client['slug']]) }}#archive" aria-label="View work for {{ $client['name'] }}">
                            <img src="{{ asset($client['logo']) }}" alt="{{ $client['name'] }}" loading="lazy" decoding="async">
                        </a>
                    @endforeach
                </div>
                <a class="text-link" href="{{ route('work.index') }}">See our work <span aria-hidden="true">→</span></a>
            </div>
        </section>
    @endif

    <section class="section section--cream" aria-labelledby="channels-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">The media world, connected</span>
                    <h2 id="channels-title" class="section-title">Choose the channels your audience actually uses.</h2>
                    <p>Seven ways in, one coordinated plan. Explore the media landscape by channel — then let us connect the right mix around your objective.</p>
                </div>
                <div class="section-heading__aside">
                    <a class="text-link" href="{{ route('media.index') }}">Browse all media <span aria-hidden="true">→</span></a>
                </div>
            </div>

            <div class="channel-mosaic">
                @foreach ($channels as $channel)
                    <a class="channel-tile channel-tile--{{ $channel['class'] }}" href="{{ route('media.index', ['type' => $channel['type']]) }}">
                        <img src="{{ \App\Support\MediaImage::url($channel['image']) }}" srcset="{{ \App\Support\MediaImage::srcset($channel['image'], [480, 960, 1536]) }}" sizes="(max-width: 560px) 92vw, (max-width: 900px) 46vw, (max-width: 1180px) 30vw, 33vw" alt="" loading="lazy" decoding="async">
                        <span class="channel-tile__icon"><x-dm-icon :name="$channel['icon']" :size="19"/></span>
                        <span class="channel-tile__arrow" aria-hidden="true">↗</span>
                        <span class="channel-tile__body">
                            <strong>{{ $channel['title'] }}</strong>
                            <small>{{ $channel['copy'] }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section process process--image" data-process aria-labelledby="process-title">
        <div class="process__bg" aria-hidden="true"><img src="{{ \App\Support\MediaImage::url('assets/img/v2/process-bg.webp') }}" alt="" loading="lazy" decoding="async"></div>
        <div class="container process__inner">
            <div class="process__intro">
                <span class="eyebrow eyebrow--pale">Not just placement</span>
                <h2 id="process-title" class="section-title">A campaign is a system, not a slot.</h2>
                <p>We connect the brief, the creative, the media and the follow-up — so each part makes the next one work harder.</p>
                <a class="button button--on-dark" href="{{ route('solutions') }}">See how it fits together <span aria-hidden="true">↗</span></a>
            </div>
            <div class="process__steps">
                <svg class="process__thread" viewBox="0 0 1200 2" preserveAspectRatio="none" aria-hidden="true">
                    <path data-process-line d="M0 1H1200" pathLength="1"/>
                </svg>
                @foreach ([
                    ['Understand', 'Audience, objective, constraints and what success actually means.', 'Brief excerpt'],
                    ['Create', 'A creative idea built for the channel, not adapted after the fact.', 'Creative frame'],
                    ['Place', 'The right media mix, formats, timing and negotiated conditions.', 'Media plan'],
                    ['Engage', 'Creator content, activations and experiences that invite a response.', 'Campaign moment'],
                    ['Convert', 'Landing pages, lead capture and follow-up workflows that keep momentum.', 'Landing page'],
                    ['Learn', 'Measurement, reporting and the next round of decisions.', 'Performance note'],
                ] as $i => $step)
                    <article class="process-step">
                        <span class="process-step__number">0{{ $i + 1 }}</span>
                        <h3>{{ $step[0] }}</h3>
                        <p>{{ $step[1] }}</p>
                        <small>{{ $step[2] }}</small>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @if ($heroFeature)
        <section class="section" aria-labelledby="work-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Featured work</span>
                        <h2 id="work-title" class="section-title">Proof from real campaigns.</h2>
                        <p>Television, radio, outdoor and production work drawn from the RA Media archive — not stock case studies.</p>
                    </div>
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('work.index') }}">Explore the archive <span aria-hidden="true">→</span></a>
                    </div>
                </div>

                <div class="featured-work">
                    <a class="work-feature" href="{{ route('work.index', ['client' => $heroFeature['client']]) }}#archive">
                        @if ($heroFeature['cover'])
                            <img src="{{ asset($heroFeature['cover']) }}" alt="A frame from {{ $heroFeature['title'] }}" loading="eager" decoding="async">
                        @else
                            <img src="{{ asset('assets/img/v2/channel-tv.webp') }}" alt="" loading="lazy" decoding="async">
                        @endif
                        <div class="work-feature__body">
                            <div class="work-feature__meta">
                                <span>{{ $heroFeature['client_name'] }}</span>
                                <span>{{ $heroFeature['channel'] }}</span>
                                <span>{{ $heroFeature['year'] }}</span>
                            </div>
                            <h3>{{ $heroFeature['title'] }}</h3>
                            <p>{{ $heroFeature['description'] }}</p>
                            <span class="text-link text-link--dark" style="margin-top:0.8rem">View this work <span aria-hidden="true">↗</span></span>
                        </div>
                    </a>

                    <div class="work-support">
                        @foreach ($supportWork as $work)
                            <a class="work-support__item" href="{{ route('work.index', ['client' => $work['client']]) }}#archive">
                                <span class="work-support__thumb">
                                    @if ($work['cover'])
                                        <img src="{{ asset($work['cover']) }}" alt="" loading="lazy" decoding="async">
                                    @elseif ($work['client_logo'])
                                        <img src="{{ asset($work['client_logo']) }}" alt="" loading="lazy" decoding="async" style="object-fit:contain;padding:0.4rem">
                                    @endif
                                </span>
                                <span>
                                    <span class="work-support__client">{{ $work['client_name'] }}</span>
                                    <h3>{{ $work['title'] }}</h3>
                                    <p>{{ $work['channel'] }} · {{ $work['year'] }}</p>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="section section--cream" aria-labelledby="personas-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">One opportunity, many ways in</span>
                    <h2 id="personas-title" class="section-title">Where do you want to go next?</h2>
                </div>
            </div>
            <div class="persona-grid">
                @foreach ($personas as $persona)
                    <a class="persona" href="{{ $persona['url'] }}">
                        <img src="{{ \App\Support\MediaImage::url($persona['image']) }}" srcset="{{ \App\Support\MediaImage::srcset($persona['image'], [480, 960, 1536]) }}" sizes="(max-width: 760px) 92vw, (max-width: 1024px) 46vw, 24vw" alt="" loading="lazy" decoding="async">
                        <span class="persona__body">
                            <h3>{{ $persona['title'] }}</h3>
                            <p>{{ $persona['copy'] }}</p>
                            <span>{{ $persona['cta'] }} <span aria-hidden="true">↗</span></span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($media->isNotEmpty())
        <section class="section" aria-labelledby="media-preview-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Media discovery</span>
                        <h2 id="media-preview-title" class="section-title">A first look at what's possible.</h2>
                        <p>Selected opportunities across broadcast, outdoor, digital, creators and custom spaces. Rates and availability are verified with the media owner before any booking.</p>
                    </div>
                    <div class="section-heading__aside">
                        <a class="text-link" href="{{ route('media.index') }}">Open the media catalogue <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="media-grid">
                    @foreach ($media as $item)
                        <x-media-card :item="$item"/>
                    @endforeach
                </div>
                <p class="tiny muted" style="margin-top:1.25rem">Indicative inventory only. No availability, rate or supplier relationship is confirmed until verified by our team.</p>
            </div>
        </section>
    @endif

    <section class="section section--cream" aria-labelledby="academy-title">
        <div class="container">
            <div class="feature-split">
                <figure class="feature-split__media">
                    <img src="{{ \App\Support\MediaImage::url('assets/img/v2/academy-hero.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/academy-hero.webp', [480, 960, 1536]) }}" sizes="(max-width: 1024px) 92vw, 46vw" alt="A facilitator leading a hands-on workshop with a small team" width="1536" height="1024" loading="lazy" decoding="async">
                    <figcaption>Practical workshops, built around real workflows.</figcaption>
                </figure>
                <div class="feature-split__body">
                    <span class="eyebrow">Dunia Media Academy</span>
                    <h2 id="academy-title" class="section-title">AI changes what a team can do when it becomes part of how they work.</h2>
                    <p>Our sessions move past the demo. Teams bring a real task, learn the tools in context, and leave with something useful — a workflow, a piece of content, a clearer decision.</p>
                    <ul class="feature-list">
                        <li>Introductory talks for leaders and wider teams</li>
                        <li>Hands-on workshops for marketing, content and operations</li>
                        <li>Custom programmes built around your tools and objectives</li>
                    </ul>
                    <a class="button button--primary" href="{{ route('academy') }}">Explore training <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="cta-title">
        <div class="container">
            <div class="cta-panel cta-panel--media">
                <div class="cta-panel__copy">
                    <span class="eyebrow">Start with a conversation</span>
                    <h2 id="cta-title">Tell us where you want your brand to go.</h2>
                    <p>Share your audience, objective and rough budget. We'll help you see which channels and creative ideas make sense together.</p>
                    <div class="cta-panel__actions">
                        <a class="button button--primary" href="{{ route('advisor') }}">Start planning <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('contact') }}">Talk to the team</a>
                    </div>
                </div>
                <figure class="cta-panel__media">
                    <img src="{{ \App\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/contact-studio.webp', [480, 960, 1536]) }}" sizes="(max-width: 760px) 92vw, 34vw" alt="A quiet creative studio meeting table with notebooks, coffee and printed contact sheets" loading="eager" decoding="async">
                </figure>
            </div>
        </div>
    </section>
@endsection
