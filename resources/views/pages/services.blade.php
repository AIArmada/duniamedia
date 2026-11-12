@extends('layouts.app')

@section('content')
    @php
        $serviceImages = [
            'media-planning-buying' => 'assets/img/v2/service-planning.webp',
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
                    'Media planning starts with the audience and the outcome, not the inventory list. We map the channels that can do a specific job — mass reach, local relevance, consideration or conversion — then negotiate the formats, timing and conditions around that plan.',
                    'Because the team also produces content and builds the follow-up systems, the plan stays connected to what happens after the first impression. Recommendations are checked against real availability, rate cards and supplier terms before anything is presented as confirmed.',
                ],
                'offers' => ['Channel strategy and media mix', 'Rate-card review and negotiation', 'Scheduling and placement coordination', 'Cross-channel campaign planning'],
                'deliverables' => ['Media plan with rationale', 'Indicative budget scenarios', 'Booking schedule and artwork specs', 'Post-campaign placement summary'],
                'proof' => ['label' => 'See campaign records', 'url' => route('work.index')],
            ],
            'branding-creative-studio' => [
                'anchor' => 'creative',
                'copy' => [
                    'Creative work is built for the place it will appear. A billboard needs one clear idea at a glance; a radio spot needs a voice and a rhythm; an advertorial needs a reason to keep reading. We design for the channel rather than shrinking one asset into every format.',
                    'The studio covers identity, campaign concepts, graphic design, copywriting, photography and audio. For teams without an in-house department, it can act as a complete creative partner; for teams with one, it can extend capacity around a launch or a specific production.',
                ],
                'offers' => ['Brand identity and design systems', 'Campaign concepts and key visuals', 'Copywriting and editorial design', 'Photography and audio production'],
                'deliverables' => ['Creative territories and routes', 'Campaign key visual and adaptations', 'Channel-ready artwork', 'Brand and usage guidelines'],
                'proof' => ['label' => 'Discuss a creative brief', 'url' => route('contact')],
            ],
            'ai-video-image-production' => [
                'anchor' => 'creative',
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
                    'Briefing, production support, approvals and reporting are handled with the same care as any other channel. The creator keeps their voice; the brand gets a clear set of deliverables and a measurement plan.',
                ],
                'offers' => ['Creator discovery and niche matching', 'Briefing and creative direction', 'Production and editing support', 'Deliverable tracking and reporting'],
                'deliverables' => ['Creator shortlist with rationale', 'Agreed brief and deliverables', 'Approved content and usage rights', 'Campaign performance summary'],
                'proof' => ['label' => 'Explore the creator network', 'url' => route('creators.index')],
            ],
            'media-asset-monetisation' => [
                'anchor' => 'placements',
                'copy' => [
                    'Many organisations own attention without realising it: a wall in a busy lobby, a screen in a waiting area, a table in a restaurant, a vehicle route, an event space. We assess whether a space can become a responsible media asset before suggesting it to advertisers.',
                    'The work covers format feasibility, audience context, ownership and permission, photography and a sellable proposition. Rights are never assumed, and a listing is only published once the practical and commercial conditions are clear.',
                ],
                'offers' => ['Space feasibility and format review', 'Audience and context assessment', 'Photography and proposition design', 'Advertiser matching and sales support'],
                'deliverables' => ['Feasibility assessment', 'Asset definition and rate basis', 'Photography and listing assets', 'Go-to-market recommendation'],
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
                'proof' => ['label' => 'Explore the academy', 'url' => route('academy')],
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
    @endphp

    <x-page-hero
        eyebrow="Our capabilities"
        title="Everything connects.<br><span class='editorial'>So can your campaign.</span>"
        lede="From reaching an audience to creating real action: media, branding, content, AI and the digital systems that carry the response."
        :image="'assets/img/v2/hero-services.webp'"
        :image-mobile="'assets/img/v2/hero-services-mobile.webp'"
        image-alt="A Malaysian creative team reviewing a storyboard and colour swatches in a production studio"
    >
        <x-slot:actions>
            <a class="button button--primary" href="{{ route('contact') }}">Discuss a brief <span aria-hidden="true">↗</span></a>
            <a class="button button--secondary" href="{{ route('solutions') }}">See the connected system</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="service-list">
                @foreach ($services as $i => $service)
                    @php($detail = $details[$service->slug] ?? null)
                    <article class="service-row" id="{{ $detail['anchor'] ?? $service->slug }}">
                        <figure class="service-row__media">
                            <img src="{{ \App\Support\MediaImage::url($serviceImages[$service->slug] ?? $service->displayImage()) }}" srcset="{{ \App\Support\MediaImage::srcset($serviceImages[$service->slug] ?? $service->displayImage(), [480, 960, 1536]) }}" sizes="(max-width: 1024px) 92vw, 45vw" alt="" loading="lazy" decoding="async">
                        </figure>
                        <div>
                            <div class="service-row__kicker"><span class="service-row__icon"><x-dm-icon :name="$serviceIcons[$service->slug] ?? 'spark'" :size="18"/></span><span class="service-row__index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ strtoupper($service->category) }}</span></div>
                            <h2>{{ $service->name }}</h2>
                            <p>{{ $service->summary }}</p>
                            @if ($detail)
                                @foreach ($detail['copy'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                                <div class="service-row__columns">
                                    <div>
                                        <h3>What we do</h3>
                                        <ul>
                                            @foreach ($detail['offers'] as $offer)<li>{{ $offer }}</li>@endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <h3>Typical deliverables</h3>
                                        <ul>
                                            @foreach ($detail['deliverables'] as $deliverable)<li>{{ $deliverable }}</li>@endforeach
                                        </ul>
                                    </div>
                                </div>
                                <a class="text-link" href="{{ $detail['proof']['url'] }}">{{ $detail['proof']['label'] }} <span aria-hidden="true">→</span></a>
                            @else
                                <a href="{{ route('contact') }}" class="text-link">Discuss this service <span aria-hidden="true">↗</span></a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="cta-panel">
                <div class="cta-panel__inner">
                    <div>
                        <span class="eyebrow">Not sure where to begin?</span>
                        <h2>Start with the outcome. We'll map the disciplines.</h2>
                        <p>Tell us what needs to change — awareness, enquiries, a launch, a team capability — and we will show which parts of the system are relevant.</p>
                    </div>
                    <div class="cta-panel__actions">
                        <a class="button button--primary" href="{{ route('advisor') }}">Use the media advisor <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('contact') }}">Talk to the team</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
