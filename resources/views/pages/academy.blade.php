@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Dunia Media Academy"
        title="AI fluency.<br><span class='editorial'>Real business advantage.</span>"
        lede="Seminars, hands-on programmes and corporate learning across AI, creativity, branding and business technology — built for the way teams actually work."
        :media="asset('assets/img/training.webp')"
    >
        <x-slot:actions>
            <a class="button button--primary" href="{{ route('contact') }}">Design a programme for my team <span aria-hidden="true">↗</span></a>
            <a class="button button--outline-light" href="#tracks">See learning tracks</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Who learning is for</span>
                    <h2 class="section-title">Different teams, different starting points.</h2>
                    <p>No two teams need the same session. The common thread is a practical outcome: something clearer, faster or more useful than before.</p>
                </div>
            </div>
            <div class="learner-grid">
                <article class="learner"><span>01</span><h3>Founders &amp; SMEs</h3><p>Understand what AI can realistically do for a small team, where to start, and what to ignore.</p></article>
                <article class="learner"><span>02</span><h3>Marketers &amp; creators</h3><p>Use AI in research, concepting, content production and campaign adaptation without losing the brand voice.</p></article>
                <article class="learner"><span>03</span><h3>Corporate teams</h3><p>Build shared workflows, review habits and a responsible approach across functions.</p></article>
                <article class="learner"><span>04</span><h3>Leaders &amp; public sector</h3><p>Make informed adoption decisions: capability, risk, governance and the changes worth prioritising.</p></article>
            </div>
        </div>
    </section>

    <section class="section section--cream" id="tracks">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Programme tracks</span>
                    <h2 class="section-title">Learning areas we can shape around you.</h2>
                    <p>These are proposed tracks for enquiry, not a published calendar. Sessions are arranged with your team and can be adapted to the tools you already use.</p>
                </div>
            </div>
            <div class="track-grid">
                @php($trackImages = ['assets/img/training.webp', 'assets/img/creator-feature.webp', 'assets/img/digital.webp'])
                @foreach ($trainings as $training)
                    <article class="track">
                        <div class="track__media">
                            <img src="{{ asset($trackImages[$loop->index] ?? $training->image) }}" alt="" loading="lazy" decoding="async">
                        </div>
                        <span class="eyebrow eyebrow--plain">{{ strtoupper($training->category) }}</span>
                        <h3>{{ $training->title }}</h3>
                        <p>{{ $training->summary }}</p>
                        <div class="track__meta">
                            <span class="tag">{{ $training->format ?? 'Workshop' }}</span>
                            <span class="tag">{{ $training->duration ?? 'Customisable' }}</span>
                        </div>
                    </article>
                @endforeach
                <article class="track">
                    <div class="track__media">
                        <img src="{{ asset('assets/img/editorial-media-mix.webp') }}" alt="" loading="lazy" decoding="async">
                    </div>
                    <span class="eyebrow eyebrow--plain">Strategy</span>
                    <h3>AI adoption for leadership</h3>
                    <p>A decision-focused session on capability, risk, governance and where adoption is worth the effort.</p>
                    <div class="track__meta"><span class="tag">Briefing</span><span class="tag">Customisable</span></div>
                </article>
                <article class="track">
                    <div class="track__media">
                        <img src="{{ asset('assets/img/digital.webp') }}" alt="" loading="lazy" decoding="async">
                    </div>
                    <span class="eyebrow eyebrow--plain">Operations</span>
                    <h3>Automation &amp; workflow design</h3>
                    <p>Map a repetitive workflow, test where automation helps, and leave with a practical next step.</p>
                    <div class="track__meta"><span class="tag">Workshop</span><span class="tag">Customisable</span></div>
                </article>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="feature-split feature-split--reverse">
                <figure class="feature-split__media">
                    <img src="{{ asset('assets/img/training.webp') }}" alt="A facilitator leading a hands-on AI workshop with a team" loading="lazy" decoding="async">
                    <figcaption>Brief → demonstration → practice → useful output → follow-up.</figcaption>
                </figure>
                <div class="feature-split__body">
                    <span class="eyebrow">How learning happens</span>
                    <h2 class="section-title">A working session, not a slideshow.</h2>
                    <p>We start with the real task. A short demonstration shows what good looks like, then participants work with their own material while we guide the decisions.</p>
                    <ul class="feature-list">
                        <li>Brief: what the team needs to do differently</li>
                        <li>Demonstration: a credible, relevant example</li>
                        <li>Practice: guided work on a real task</li>
                        <li>Output: something the team can use immediately</li>
                    </ul>
                    <a class="button button--primary" href="{{ route('contact') }}">Discuss a session <span aria-hidden="true">↗</span></a>
                </div>
            </div>

            <div style="margin-top:clamp(2.5rem,5vw,4rem)">
                <span class="eyebrow">Formats</span>
                <h2 class="section-title">Choose the depth that fits.</h2>
                <table class="format-table">
                    <thead>
                        <tr><th scope="col">Format</th><th scope="col">Best for</th><th scope="col">Shape</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Introductory talk</td><td>Leadership, all-hands, conference sessions</td><td>45–90 minutes, demonstration-led</td></tr>
                        <tr><td>Half-day workshop</td><td>A team exploring one workflow or toolset</td><td>Guided practice with a shared output</td></tr>
                        <tr><td>Full-day practical</td><td>Marketing, content or operations teams</td><td>Multiple exercises, review and planning</td></tr>
                        <tr><td>Multi-day programme</td><td>Teams building lasting capability</td><td>Progressive sessions with between-session work</td></tr>
                        <tr><td>Custom corporate programme</td><td>Cross-functional adoption or change</td><td>Scoped around your tools, policies and goals</td></tr>
                    </tbody>
                </table>
                <p class="tiny muted" style="margin-top:0.9rem">No public dates or prices are listed. Programmes are arranged on request and quoted after scoping.</p>
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="cta-panel">
                <div class="cta-panel__inner">
                    <div>
                        <span class="eyebrow">Design a programme</span>
                        <h2>What should your team be able to do afterwards?</h2>
                        <p>Tell us the audience, the objective and the preferred timing. We will suggest a format and a practical outline.</p>
                    </div>
                    <div class="cta-panel__actions">
                        <a class="button button--primary" href="{{ route('contact') }}">Talk about training <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('services') }}">See related services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
