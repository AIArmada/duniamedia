@extends('layouts.app')

@section('content')
    @php
        $serviceImages = [
            'media-planning-buying' => 'assets/img/v2/medium-outdoor.webp',
            'branding-creative-studio' => 'assets/img/v2/service-creative.webp',
            'ai-video-image-production' => 'assets/img/v2/service-ai.webp',
            'websites-conversion-systems' => 'assets/img/v2/service-web.webp',
            'creator-influencer-campaigns' => 'assets/img/v2/service-creators.webp',
            'media-asset-monetisation' => 'assets/img/v2/service-spaces.webp',
            'ai-seminars-corporate-training' => 'assets/img/v2/academy-hero.webp',
            'campaign-analytics-automation' => 'assets/img/v2/service-analytics.webp',
        ];
        $serviceIcons = [
            'media-planning-buying' => 'compass',
            'branding-creative-studio' => 'spark',
            'ai-video-image-production' => 'play',
            'websites-conversion-systems' => 'code',
            'creator-influencer-campaigns' => 'users',
            'media-asset-monetisation' => 'building',
            'ai-seminars-corporate-training' => 'briefcase',
            'campaign-analytics-automation' => 'chart',
        ];

        $details = [
            'media-planning-buying' => [
                'anchor' => 'planning',
                'copy' => [
                    'Media planning starts with the audience and the outcome, not the inventory list. We map the media that can do a specific job — mass reach, local relevance, consideration or conversion — then negotiate the formats, timing and conditions around that plan.',
                    'Because the team also produces content and builds the follow-up systems, the plan stays connected to what happens after the first impression. Recommendations are checked against real availability, rate cards and media owner terms before anything is presented as confirmed.',
                ],
                'offers' => ['Media strategy and media mix', 'Rate-card review and negotiation', 'Scheduling and placement coordination', 'Cross-medium campaign planning'],
                'deliverables' => ['Media plan with rationale', 'Indicative budget scenarios', 'Booking schedule and artwork specs', 'Post-campaign placement summary'],
                'proof' => ['label' => 'See campaign records', 'url' => route('work.index')],
            ],
            'branding-creative-studio' => [
                'anchor' => 'creative',
                'copy' => [
                    'Creative work is built for the place it will appear. A billboard needs one clear idea at a glance; a radio spot needs a voice and a rhythm; an advertorial needs a reason to keep reading. We design for the medium rather than shrinking one asset into every format.',
                    'The studio covers identity, campaign concepts, graphic design, copywriting, photography and audio. For teams without an in-house department, it can act as a complete creative partner; for teams with one, it can extend capacity around a launch or a specific production.',
                ],
                'offers' => ['Brand identity and design systems', 'Campaign concepts and key visuals', 'Copywriting and editorial design', 'Photography and audio production'],
                'deliverables' => ['Creative territories and routes', 'Campaign key visual and adaptations', 'Channel-ready artwork', 'Brand and usage guidelines'],
                'proof' => ['label' => 'Discuss a creative brief', 'url' => route('contact')],
            ],
            'ai-video-image-production' => [
                'anchor' => 'ai-production',
                'copy' => [
                    'AI is useful when it removes a real bottleneck — a fast concept visual, a product scene that would be impractical to shoot, a set of language variants, or a rough cut that helps a team decide. It is not a substitute for a clear idea or a responsible production process.',
                    'We combine generative tools with conventional filming, editing and sound where each is strongest. Clients see the workflow, the source material and the approvals, so the final output is something the team can defend and reuse.',
                ],
                'offers' => ['Concept and storyboard visualisation', 'AI-assisted image and video production', 'Voice, music and sound workflows', 'Content adaptation and versioning'],
                'deliverables' => ['Concept frames and animatics', 'Finished video or image assets', 'Channel aspect-ratio versions', 'Source and usage notes'],
                'proof' => ['label' => 'See production work', 'url' => route('work.index')],
            ],
            'websites-conversion-systems' => [
                'anchor' => 'technology',
                'copy' => [
                    'A campaign often creates a moment of attention. What happens next determines whether it becomes an enquiry, a visit or a sale. We build the landing pages, forms, CRM connections and follow-up workflows that keep that moment from ending at the click.',
                    'Work is scoped around the actual journey: what the visitor needs to understand, what information the sales team needs, and how the handover happens. Analytics and consent are part of the build, not an afterthought.',
                ],
                'offers' => ['Campaign landing pages and websites', 'Lead capture and CRM integration', 'Automation and follow-up workflows', 'Analytics, events and dashboards'],
                'deliverables' => ['Sitemap and journey map', 'Designed and built pages', 'Connected forms and CRM records', 'Measurement plan and handover'],
                'proof' => ['label' => 'Talk about a build', 'url' => route('contact')],
            ],
            'creator-influencer-campaigns' => [
                'anchor' => 'creators',
                'copy' => [
                    'Creator campaigns work when the fit is real. We start with the audience and the creative role the creator will play, then look for a voice that can carry it without sounding like an advert.',
                    'Briefing, production support, approvals and reporting are handled with the same care as any other medium. The creator keeps their voice; the brand gets a clear set of deliverables and a measurement plan.',
                ],
                'offers' => ['Creator discovery and niche matching', 'Briefing and creative direction', 'Production and editing support', 'Deliverable tracking and reporting'],
                'deliverables' => ['Creator shortlist with rationale', 'Agreed brief and deliverables', 'Approved content and usage rights', 'Campaign performance summary'],
                'proof' => ['label' => 'Explore the creator network', 'url' => route('creators.index')],
            ],
            'media-asset-monetisation' => [
                'anchor' => 'placements',
                'copy' => [
                    'Many organisations own attention without realising it: a wall in a busy lobby, a screen in a waiting area, a table in a restaurant, a vehicle route, an event space. We assess whether a space can become a responsible media asset before suggesting it to advertisers.',
                    'The work covers format feasibility, audience context, ownership and permission, photography and a sellable proposition. Rights are never assumed, and an opportunity is only published once the practical and commercial conditions are clear.',
                ],
                'offers' => ['Space feasibility and format review', 'Audience and context assessment', 'Photography and proposition design', 'Advertiser matching and sales support'],
                'deliverables' => ['Feasibility assessment', 'Asset definition and rate basis', 'Photography and opportunity assets', 'Go-to-market recommendation'],
                'proof' => ['label' => 'List your space', 'url' => route('assets.join')],
            ],
            'ai-seminars-corporate-training' => [
                'anchor' => 'training',
                'copy' => [
                    'Teams do not need another tour of AI tools. They need to know which tasks are worth changing, what good output looks like, and where human review still matters. Our sessions are built around those decisions.',
                    'Formats range from an introductory talk to a hands-on workshop or a multi-session programme. Participants work with their own material and leave with a practical output, not just notes.',
                ],
                'offers' => ['Leadership briefings and introductory talks', 'Hands-on workshops for teams', 'Custom programmes around real workflows', 'Follow-up resources and review'],
                'deliverables' => ['Programme outline and outcomes', 'Facilitated session or series', 'Worked examples and templates', 'Follow-up reference material'],
                'proof' => ['label' => 'Discuss a training brief', 'url' => route('contact')],
            ],
            'campaign-analytics-automation' => [
                'anchor' => 'measurement',
                'copy' => [
                    'Measurement should answer a decision, not decorate a report. We agree what the campaign is trying to change, what can realistically be observed, and which source will be trusted for each number.',
                    'Offline placements, online response and sales follow-up often live in different systems. We connect what can be connected, state clearly what cannot, and build a reporting rhythm the team can actually use.',
                ],
                'offers' => ['Measurement frameworks and KPIs', 'Campaign tracking and event design', 'Lead handling and CRM reporting', 'Dashboards and review sessions'],
                'deliverables' => ['Measurement plan', 'Tracking and event setup', 'Campaign dashboard', 'Learning and next-step report'],
                'proof' => ['label' => 'Talk about measurement', 'url' => route('contact')],
            ],
        ];
        $image = \App\Features\Catalog\Support\MediaImage::class;
        $featured = $services->firstWhere('slug', 'media-planning-buying');
        $capabilities = $services->filter(fn ($service) => $service->slug !== 'media-planning-buying')->values();
        $objectives = [
            ['megaphone', 'Launch a product or service', route('advisor', ['objective' => 'launch'])],
            ['users', 'Build brand awareness', route('advisor', ['objective' => 'awareness'])],
            ['target', 'Generate leads and sales', route('advisor', ['objective' => 'leads'])],
            ['building', 'Monetise my media space', route('assets.join')],
            ['briefcase', 'Develop my team’s skills', route('contact')],
        ];
    @endphp

    <div class="services-editorial">
        <section class="services-hero" aria-labelledby="services-title">
            <div class="services-hero__copy">
                <span class="services-kicker">Our services</span>
                <h1 id="services-title">Good ideas.<br>Bigger reach.<br><em>Real impact.</em></h1>
                <p>From the first creative idea to the final customer action. Media, production, people and technology, together.</p>
                <div class="services-actions">
                    <a class="button button--primary" href="#capabilities">Explore our services <span aria-hidden="true">↓</span></a>
                    <a class="button button--secondary" href="{{ route('contact') }}">Discuss your brief <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <picture class="services-hero__visual">
                <source media="(max-width: 700px)" srcset="{{ $image::srcset('assets/img/v2/hero-services-mobile.webp', [480, 640, 960]) }}" sizes="100vw">
                <img src="{{ $image::url('assets/img/v2/hero-services.webp') }}" srcset="{{ $image::srcset('assets/img/v2/hero-services.webp', [640, 960, 1280]) }}" sizes="(max-width: 900px) 100vw, 61vw" alt="A creative team reviewing production concepts in a studio" fetchpriority="high" decoding="async">
            </picture>
        </section>

        @if ($featured)
            <section class="container services-feature-section" id="planning" aria-labelledby="services-feature-title">
                <article class="services-feature">
                    <div class="services-feature__visual">
                        <img src="{{ $image::url($serviceImages[$featured->slug]) }}" srcset="{{ $image::srcset($serviceImages[$featured->slug], [480, 640, 960, 1280]) }}" sizes="(max-width: 900px) 92vw, 45vw" alt="Outdoor advertising in the city" loading="lazy" decoding="async">
                    </div>
                    <div class="services-feature__copy">
                        <span class="services-kicker">Featured service</span>
                        <h2 id="services-feature-title">{{ $featured->name }}</h2>
                        <p>{{ $featured->summary }} Get your message in front of the right audience, at the right time and on the right platforms.</p>
                        <div class="services-feature__benefits">
                            @foreach ([['compass', 'Strategic planning'], ['layers', 'Multi-platform buying'], ['chart', 'Performance & reporting']] as [$icon, $label])
                                <span><x-dm-icon :name="$icon" :size="23"/>{{ $label }}</span>
                            @endforeach
                        </div>
                        <a class="button button--primary" href="{{ route('media.index') }}">Explore media opportunities <span aria-hidden="true">↗</span></a>
                        @include('pages.partials.service-details', ['detail' => $details[$featured->slug]])
                    </div>
                </article>
            </section>
        @endif

        <section class="container services-capabilities" id="capabilities" aria-labelledby="services-capabilities-title">
            <div class="services-section-heading">
                <div>
                    <span class="services-kicker">Our capabilities</span>
                    <h2 id="services-capabilities-title">Explore our services</h2>
                </div>
                <p>A complete set of creative, media and digital solutions to grow your brand, audience and business.</p>
            </div>
            <div class="services-grid">
                @foreach ($capabilities as $service)
                    @php
                        $detail = $details[$service->slug] ?? null;
                        $path = $serviceImages[$service->slug] ?? $service->displayImage();
                    @endphp
                    <article class="services-card" id="{{ $detail['anchor'] ?? $service->slug }}">
                        <div class="services-card__visual">
                            <img src="{{ $image::url($path) }}" @if ($srcset = $image::srcset($path, [480, 640, 960])) srcset="{{ $srcset }}" @endif sizes="(max-width: 600px) 92vw, (max-width: 1024px) 46vw, 30vw" alt="" loading="lazy" decoding="async">
                        </div>
                        <div class="services-card__body">
                            <div class="services-card__heading">
                                <x-dm-icon :name="$serviceIcons[$service->slug] ?? 'spark'" :size="25"/>
                                <h3>{{ $service->name }}</h3>
                            </div>
                            <p>{{ $service->summary }}</p>
                            @include('pages.partials.service-details', ['detail' => $detail])
                        </div>
                    </article>
                @endforeach
            </div>
            @if ($capabilities->isEmpty())
                <a class="button button--secondary" href="{{ route('contact') }}">Discuss your requirements</a>
            @endif
        </section>

        <section class="services-objectives" aria-labelledby="services-objectives-title">
            <div class="container services-objectives__inner">
                <div>
                    <h2 id="services-objectives-title">Not sure what you need yet?</h2>
                    <p>Tell us your goal and we’ll help you find the right mix of services and media.</p>
                </div>
                <div class="services-objectives__links">
                    @foreach ($objectives as [$icon, $label, $url])
                        <a href="{{ $url }}"><x-dm-icon :name="$icon" :size="32"/><span>{{ $label }}</span></a>
                    @endforeach
                </div>
            </div>
        </section>

        @if ($featuredFilms->isNotEmpty())
            <section class="container services-work" aria-labelledby="services-work-title">
                <div class="services-work__intro">
                    <span class="services-kicker">Selected work</span>
                    <h2 id="services-work-title">See the work.<br>Not just the words.</h2>
                    <p>Real campaigns and productions for Malaysian brands and public institutions.</p>
                    <a class="button button--secondary" href="{{ route('work.index') }}">Explore our work <span aria-hidden="true">↗</span></a>
                </div>
                <div class="services-work__grid">
                    @foreach ($featuredFilms as $film)
                        <a class="services-work-card" href="{{ route('work.index') }}#work-media-{{ $film['key'] }}">
                            <img src="{{ $image::url($film['poster']) }}" alt="" loading="lazy" decoding="async">
                            <div class="services-work-card__caption">
                                <span><small>{{ $film['date'] }}</small><strong>{{ $film['title'] }}</strong></span>
                                <x-dm-icon name="play" :size="30"/>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="services-final" aria-labelledby="services-final-title">
            <div class="container services-final__inner">
                <div>
                    <h2 id="services-final-title">Ready to make an impact?</h2>
                    <p>Let’s turn your idea into a campaign people see, remember and act on.</p>
                </div>
                <div class="services-actions">
                    <a class="button button--on-dark" href="{{ route('advisor') }}">Plan a campaign <span aria-hidden="true">↗</span></a>
                    <a class="button button--outline-light" href="{{ route('contact') }}">Talk to our team <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </section>
    </div>
@endsection
