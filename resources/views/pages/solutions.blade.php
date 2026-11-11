@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Creative × technology × intelligence"
        title="Better reach is <span class='editorial'>only the beginning</span>."
        lede="A placement can create attention. A connected campaign turns that attention into something the business can see: an enquiry, a visit, a sale, a stronger relationship."
    >
        <x-slot:actions>
            <a class="button button--primary" href="{{ route('advisor') }}">Plan a connected campaign <span aria-hidden="true">↗</span></a>
            <a class="button button--secondary" href="{{ route('services') }}">Explore capabilities</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">The connected system</span>
                    <h2 class="section-title">Six stages. One shared objective.</h2>
                    <p>Each stage has its own craft, but none of them works in isolation. The plan, the creative, the media, the response and the learning are designed together.</p>
                </div>
            </div>

            <div class="solution-grid">
                @foreach ([
                    ['Discover', 'Understand the audience, the objective, the constraints and what the business actually needs to happen.'],
                    ['Strategise', 'Choose the channel roles, the regional relevance, the content plan and the measurement approach.'],
                    ['Produce', 'Create the visual, video, audio and copy assets — using AI-assisted workflows only where they genuinely help.'],
                    ['Distribute', 'Place the work across broadcast, out-of-home, editorial, digital, creators and custom media.'],
                    ['Convert', 'Carry the response into landing pages, lead capture, CRM and a follow-up process that someone owns.'],
                    ['Improve', 'Read the evidence, test the next creative move and feed the learning back into the plan.'],
                ] as $i => $step)
                    <article class="solution-card">
                        <span class="solution-card__number">0{{ $i + 1 }}</span>
                        <h3>{{ $step[0] }}</h3>
                        <p>{{ $step[1] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="feature-split">
                <div class="feature-split__body">
                    <span class="eyebrow">What it looks like in practice</span>
                    <h2 class="section-title">The campaign is the tip. The system is the work.</h2>
                    <p>A television interview, an outdoor site or a creator film is the visible part. Behind it sit the brief, the approvals, the artwork versions, the booking schedule, the landing page, the follow-up and the report.</p>
                    <p>We keep those pieces in one conversation. That reduces the gap between the idea that was approved and the campaign that actually runs.</p>
                    <ul class="feature-list">
                        <li>One brief shared by creative, media and technology</li>
                        <li>Assets planned for each channel from the start</li>
                        <li>Response handled while the campaign is live</li>
                        <li>Learning captured before the next round begins</li>
                    </ul>
                    <a class="button button--primary" href="{{ route('contact') }}">Talk through your next campaign <span aria-hidden="true">↗</span></a>
                </div>
                <figure class="feature-split__media">
                    <img src="{{ asset('assets/img/training.webp') }}" alt="A team working through a campaign plan together" loading="lazy" decoding="async">
                    <figcaption>Strategy, production and delivery in one room.</figcaption>
                </figure>
            </div>

            <div class="solution-visual">
                <figure>
                    <img src="{{ asset('assets/img/outdoor.webp') }}" alt="A large roadside LED billboard above a Kuala Lumpur highway" loading="lazy" decoding="async">
                    <figcaption>Place: a real outdoor moment in the city.</figcaption>
                </figure>
                <figure>
                    <img src="{{ asset('assets/img/creators.webp') }}" alt="A creator recording a product-led story at home" loading="lazy" decoding="async">
                    <figcaption>Engage: creator content built for the audience.</figcaption>
                </figure>
                <figure>
                    <img src="{{ asset('assets/img/digital.webp') }}" alt="A digital interface on a laptop screen" loading="lazy" decoding="async">
                    <figcaption>Convert: the digital journey after the impression.</figcaption>
                </figure>
                <figure>
                    <img src="{{ asset('assets/img/editorial-measurement.webp') }}" alt="Campaign measurement notes and charts" loading="lazy" decoding="async">
                    <figcaption>Learn: reporting that answers a decision.</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="section section--dark">
        <div class="container">
            <div class="cta-panel__inner">
                <div>
                    <span class="eyebrow eyebrow--pale">Production, media and technology</span>
                    <h2 class="section-title">Built around a shared objective.</h2>
                    <p style="max-width:620px;margin-top:0.8rem;color:rgba(255,248,247,.66)">Bring us the outcome and the constraints. We will help decide which channels, creative and systems deserve the investment.</p>
                </div>
                <div class="cta-panel__actions">
                    <a class="button button--on-dark" href="{{ route('advisor') }}">Start planning <span aria-hidden="true">↗</span></a>
                    <a class="button button--outline-light" href="{{ route('services') }}">See all services</a>
                </div>
            </div>
        </div>
    </section>
@endsection
