@extends('layouts.app')

@section('content')
    @php
        $objectives = [
            ['Build awareness', 'Get your brand noticed by the right audience.', 'megaphone'],
            ['Generate leads', 'Attract and capture qualified prospects.', 'users'],
            ['Launch a product', 'Create demand for your new offering.', 'spark'],
            ['Grow sales', 'Turn customer interest into action.', 'chart'],
        ];
        $capabilities = [
            ['Strategy', 'Insights, audience research and campaign planning.', 'target', 'service-planning', route('services').'#planning'],
            ['Creative', 'Branding, design, video, audio and content production.', 'camera', 'service-creative', route('services').'#creative'],
            ['Media', 'TV, radio, outdoor, print and digital placements.', 'layers', 'hero-media', route('media.index')],
            ['Influence', 'Creators, talent and community engagement.', 'users', 'service-creators', route('creators.index')],
            ['Technology', 'Websites, digital systems, AI and automation.', 'code', 'service-web', route('services').'#technology'],
            ['Measurement', 'Campaign insights, performance tracking and optimisation.', 'chart', 'service-analytics', route('services').'#measurement'],
        ];
        $stages = [
            ['Discover', 'Understand your brand, market and audience.', 'search'],
            ['Strategise', 'Turn insights into a clear campaign plan.', 'document'],
            ['Produce', 'Create content and assets for each channel.', 'camera'],
            ['Distribute', 'Activate the right media and channels.', 'radio'],
            ['Convert', 'Connect attention to leads and customer action.', 'target'],
            ['Improve', 'Measure results and refine the next move.', 'chart'],
        ];
        $example = [
            ['Build awareness', 'Introduce a new product through high-impact media placements.', 'medium-outdoor'],
            ['Create engagement', 'Bring the product closer to its audience through creator content.', 'medium-creators'],
            ['Drive action', 'Continue the journey with a focused landing page and clear next steps.', 'service-web'],
        ];
    @endphp

    <div class="connected-solutions">
        <section class="connected-hero" aria-labelledby="connected-title">
            <div class="container connected-hero__inner">
                <div class="connected-hero__copy">
                    <span class="eyebrow">Integrated campaign solutions</span>
                    <h1 id="connected-title">More than media.<em>Everything connected.</em></h1>
                    <p>From strategy and creative to media, technology and measurable results.</p>
                    <div class="connected-actions">
                        <a class="button button--primary" href="{{ route('advisor') }}">Plan your campaign <span aria-hidden="true">→</span></a>
                        <a class="button button--secondary" href="#connected-capabilities">Explore our capabilities</a>
                    </div>
                </div>
                <picture class="connected-hero__image">
                    <source media="(max-width: 760px)" srcset="{{ asset('assets/img/v2/solutions-production-mobile-480.webp') }} 480w, {{ asset('assets/img/v2/solutions-production-mobile-640.webp') }} 640w, {{ asset('assets/img/v2/solutions-production-mobile-960.webp') }} 960w" sizes="100vw">
                    <img src="{{ asset('assets/img/v2/solutions-production-960.webp') }}" srcset="{{ asset('assets/img/v2/solutions-production-640.webp') }} 640w, {{ asset('assets/img/v2/solutions-production-960.webp') }} 960w, {{ asset('assets/img/v2/solutions-production-1280.webp') }} 1280w" sizes="(max-width: 760px) 100vw, 62vw" alt="" fetchpriority="high" decoding="async">
                </picture>
            </div>
        </section>

        <section class="container connected-section" aria-labelledby="connected-objectives-title">
            <div class="connected-heading">
                <h2 id="connected-objectives-title">What do you want to achieve?</h2>
                <p>Whether you’re building a brand, reaching new customers or driving sales, we’ll help you find the right mix of media, creative and technology.</p>
            </div>
            <div class="connected-objectives">
                @foreach ($objectives as [$title, $description, $icon])
                    <article class="connected-objective">
                        <x-dm-icon :name="$icon" :size="34"/>
                        <div><h3>{{ $title }}</h3><p>{{ $description }}</p></div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container connected-section" id="connected-capabilities" aria-labelledby="connected-capabilities-title">
            <div class="connected-heading">
                <h2 id="connected-capabilities-title">Six connected capabilities</h2>
                <p>Strategy, creativity, media, influence, technology and measurement working together towards a shared objective.</p>
            </div>
            <div class="connected-capabilities">
                @foreach ($capabilities as [$title, $description, $icon, $image, $url])
                    <a class="connected-capability" href="{{ $url }}">
                        <img src="{{ asset('assets/img/v2/'.$image.'-640.webp') }}" srcset="{{ asset('assets/img/v2/'.$image.'-480.webp') }} 480w, {{ asset('assets/img/v2/'.$image.'-640.webp') }} 640w, {{ asset('assets/img/v2/'.$image.'-960.webp') }} 960w" sizes="(max-width: 760px) 92vw, (max-width: 1024px) 46vw, 30vw" alt="" loading="lazy" decoding="async">
                        <div class="connected-capability__copy">
                            <div><h3><x-dm-icon :name="$icon" :size="26"/>{{ $title }}</h3><p>{{ $description }}</p></div>
                            <span class="connected-capability__arrow" aria-hidden="true">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="container connected-section" aria-labelledby="connected-process-title">
            <div class="connected-heading">
                <h2 id="connected-process-title">One campaign. One connected system.</h2>
                <p>A clear process from your first brief to the results that inform your next campaign.</p>
            </div>
            <ol class="connected-stages">
                @foreach ($stages as [$title, $description, $icon])
                    <li class="connected-stage">
                        <x-dm-icon :name="$icon" :size="30"/>
                        <h3>{{ $title }}</h3>
                        <p>{{ $description }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="container connected-section" aria-labelledby="connected-example-title">
            <div class="connected-heading">
                <h2 id="connected-example-title">How it works</h2>
                <p>A product launch example: from the first impression to engagement and customer response.</p>
            </div>
            <div class="connected-examples">
                @foreach ($example as [$title, $description, $image])
                    <article class="connected-example">
                        <img src="{{ asset('assets/img/v2/'.$image.'-640.webp') }}" srcset="{{ asset('assets/img/v2/'.$image.'-480.webp') }} 480w, {{ asset('assets/img/v2/'.$image.'-640.webp') }} 640w, {{ asset('assets/img/v2/'.$image.'-960.webp') }} 960w" sizes="(max-width: 760px) 92vw, 30vw" alt="" loading="lazy" decoding="async">
                        <div><h3>{{ $title }}</h3><p>{{ $description }}</p></div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container connected-section connected-final" aria-labelledby="connected-final-title">
            <div class="connected-final__panel">
                <h2 id="connected-final-title">Let’s build your next campaign.</h2>
                <p>Tell us your goals and we’ll put together the right media, creative and technology solution for you.</p>
                <a class="button button--on-dark" href="{{ route('advisor') }}">Start planning <span aria-hidden="true">→</span></a>
            </div>
        </section>
    </div>
@endsection
