<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#300020">
<meta name="description" content="{{ $metaDescription ?? 'Dunia Media connects media, creative, technology and creators — so every channel works as one connected campaign. A digital brand initiative by RA Media Relations Sdn Bhd (948761-W).' }}">
<title>{{ $pageTitle ?? 'Dunia Media — All Media. Bigger Possibilities.' }}</title>
<link rel="canonical" href="{{ url()->current() }}">
<link rel="icon" href="{{ asset('assets/brand/favicon.svg') }}" type="image/svg+xml">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Dunia Media">
<meta property="og:title" content="{{ $pageTitle ?? 'Dunia Media — All Media. Bigger Possibilities.' }}">
<meta property="og:description" content="{{ $metaDescription ?? 'Media, creative, technology and human stories — brought together for work that matters.' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('assets/brand/og-dunia-media.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:image" content="{{ asset('assets/brand/og-dunia-media.png') }}">
<meta name="twitter:card" content="summary_large_image">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Dunia Media',
    'url' => url('/'),
    'logo' => asset('assets/brand/logo-horizontal.svg'),
    'description' => 'Dunia Media connects media, creative, technology and creators as one connected campaign. A digital brand initiative by RA Media Relations Sdn Bhd.',
    'parentOrganization' => [
        '@type' => 'Organization',
        'name' => 'RA Media Relations Sdn Bhd',
        'identifier' => '948761-W',
    ],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => '19B, Jalan Wan Kadir 2, Taman Tun Dr Ismail',
        'addressLocality' => 'Kuala Lumpur',
        'postalCode' => '60000',
        'addressCountry' => 'MY',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if (request()->routeIs('home'))
<link rel="preload" as="image" href="{{ asset('assets/img/hero-media-city.webp') }}" fetchpriority="high">
@endif
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
</head>
<body @class(['route-home' => request()->routeIs('home')])>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" data-site-header>
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Dunia Media home">
            <img src="{{ asset('assets/brand/logo-horizontal.svg') }}" width="184" height="51" alt="Dunia Media" decoding="async">
        </a>

        <nav class="primary-nav" aria-label="Primary navigation">
            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('media.*') ? 'is-active' : '' }}">
                    Media
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('media.index') }}">Browse all media <span>TV, radio, out-of-home, digital, print and more</span></a>
                    <a href="{{ route('media.index', ['type' => 'TV & Broadcast']) }}">TV &amp; Broadcast <span>Free-to-air, pay-TV, news and studio formats</span></a>
                    <a href="{{ route('media.index', ['type' => 'Radio']) }}">Radio &amp; Audio <span>National, regional and drive-time audiences</span></a>
                    <a href="{{ route('media.index', ['type' => 'Outdoor']) }}">Outdoor &amp; DOOH <span>Billboards, LED screens, transit and airports</span></a>
                    <a href="{{ route('media.index', ['type' => 'Digital']) }}">Digital &amp; Publishing <span>Display, video, editorial and social</span></a>
                    <a href="{{ route('media.index', ['type' => 'Print']) }}">Print &amp; Publishing <span>Newspapers, magazines and advertorials</span></a>
                    <a href="{{ route('media.index', ['type' => 'Creators']) }}">Creators &amp; Talent <span>Niche voices, communities and collaborations</span></a>
                    <a href="{{ route('advisor') }}">Media advisor <span>Start with your objective, not the inventory</span></a>
                </div>
            </details>

            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('services', 'solutions') ? 'is-active' : '' }}">
                    Solutions
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('solutions') }}">Connected campaign system <span>Understand, create, place, engage, convert, learn</span></a>
                    <a href="{{ route('services') }}#planning">Planning &amp; placement <span>Strategy, buying, negotiation and scheduling</span></a>
                    <a href="{{ route('services') }}#creative">Content &amp; production <span>Film, audio, design and editorial</span></a>
                    <a href="{{ route('services') }}#technology">Digital systems &amp; AI <span>Websites, CRM, automation and practical AI</span></a>
                    <a href="{{ route('services') }}#measurement">Campaign measurement <span>Tracking, dashboards and useful reporting</span></a>
                    <a href="{{ route('services') }}">All services <span>The full capability overview</span></a>
                </div>
            </details>

            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('creators.*') ? 'is-active' : '' }}">
                    Creators
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('creators.index') }}">Find creators <span>Explore the network by niche and platform</span></a>
                    <a href="{{ route('creators.join') }}">Join the network <span>For independent creators and talent</span></a>
                </div>
            </details>

            <a class="nav-link {{ request()->routeIs('academy') ? 'is-active' : '' }}" href="{{ route('academy') }}">Academy</a>
            <a class="nav-link {{ request()->routeIs('work.*') ? 'is-active' : '' }}" href="{{ route('work.index') }}">Our Work</a>
            <a class="nav-link {{ request()->routeIs('insights*') ? 'is-active' : '' }}" href="{{ route('insights') }}">Insights</a>
            <a class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">About</a>
        </nav>

        <div class="header-actions">
            <a class="header-search" href="{{ route('media.index') }}" aria-label="Search media">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.6"/><path d="m16 16 4 4"/></svg>
            </a>
            <a class="button button--primary header-cta" href="{{ route('advisor') }}">Plan a Campaign <span aria-hidden="true">↗</span></a>
            <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-nav">
                <span class="sr-only">Open menu</span>
                <span class="menu-toggle__bars" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>
        </div>
    </div>

    <div class="mobile-nav" id="mobile-nav" data-mobile-nav hidden>
        <details class="mobile-nav__group">
            <summary>Media</summary>
            <div class="mobile-nav__sub">
                <a href="{{ route('media.index') }}">Browse all media</a>
                <a href="{{ route('media.index', ['type' => 'TV & Broadcast']) }}">TV &amp; Broadcast</a>
                <a href="{{ route('media.index', ['type' => 'Radio']) }}">Radio &amp; Audio</a>
                <a href="{{ route('media.index', ['type' => 'Outdoor']) }}">Outdoor &amp; DOOH</a>
                <a href="{{ route('media.index', ['type' => 'Digital']) }}">Digital &amp; Publishing</a>
                <a href="{{ route('media.index', ['type' => 'Print']) }}">Print</a>
                <a href="{{ route('media.index', ['type' => 'Creators']) }}">Creators &amp; Talent</a>
                <a href="{{ route('advisor') }}">Media advisor</a>
            </div>
        </details>
        <details class="mobile-nav__group">
            <summary>Solutions</summary>
            <div class="mobile-nav__sub">
                <a href="{{ route('solutions') }}">Connected campaign system</a>
                <a href="{{ route('services') }}">All services</a>
                <a href="{{ route('services') }}#planning">Planning &amp; placement</a>
                <a href="{{ route('services') }}#creative">Content &amp; production</a>
                <a href="{{ route('services') }}#technology">Digital systems &amp; AI</a>
                <a href="{{ route('services') }}#measurement">Campaign measurement</a>
            </div>
        </details>
        <details class="mobile-nav__group">
            <summary>Creators</summary>
            <div class="mobile-nav__sub">
                <a href="{{ route('creators.index') }}">Find creators</a>
                <a href="{{ route('creators.join') }}">Join the network</a>
            </div>
        </details>
        <a class="mobile-nav__direct" href="{{ route('academy') }}">Academy</a>
        <a class="mobile-nav__direct" href="{{ route('work.index') }}">Our Work</a>
        <a class="mobile-nav__direct" href="{{ route('insights') }}">Insights</a>
        <a class="mobile-nav__direct" href="{{ route('about') }}">About</a>
        <div class="mobile-nav__actions">
            <a class="button button--primary" href="{{ route('advisor') }}">Plan a Campaign <span aria-hidden="true">↗</span></a>
            <a class="button button--secondary" href="{{ route('contact') }}">Talk to the team</a>
        </div>
    </div>
</header>

<main id="main">
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-lead">
            <div>
                <span class="eyebrow eyebrow--pale">All media. Bigger possibilities.</span>
                <h2>Every channel, connected to your next move.</h2>
                <p>Media, creative, technology, and human stories — brought together for work that matters.</p>
            </div>
            <div class="footer-lead__actions">
                <a class="button button--on-dark" href="{{ route('advisor') }}">Plan your campaign <span aria-hidden="true">↗</span></a>
                <a class="button button--outline-light" href="{{ route('contact') }}">Talk to our team</a>
            </div>
        </div>

        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('assets/brand/logo-horizontal-reversed.svg') }}" width="176" height="49" alt="Dunia Media" loading="lazy" decoding="async">
                <p>Media, creativity, technology and AI — connected through one platform. A digital brand initiative by RA Media Relations Sdn Bhd (948761-W).</p>
            </div>
            <div class="footer-col">
                <strong>Explore</strong>
                <a href="{{ route('media.index') }}">Media opportunities</a>
                <a href="{{ route('creators.index') }}">Creator network</a>
                <a href="{{ route('work.index') }}">Our work</a>
                <a href="{{ route('insights') }}">Insights</a>
            </div>
            <div class="footer-col">
                <strong>Solutions</strong>
                <a href="{{ route('solutions') }}">Connected campaigns</a>
                <a href="{{ route('services') }}">All services</a>
                <a href="{{ route('advisor') }}">Media advisor</a>
                <a href="{{ route('academy') }}">AI &amp; training</a>
            </div>
            <div class="footer-col">
                <strong>Work with us</strong>
                <a href="{{ route('quote.builder') }}">Build a proposal</a>
                <a href="{{ route('creators.join') }}">Join as a creator</a>
                <a href="{{ route('assets.join') }}">List your space</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
            <div class="footer-col">
                <strong>Company</strong>
                <a href="{{ route('about') }}">About RA Media</a>
                <a href="{{ route('about') }}#company-information">Kuala Lumpur office</a>
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="{{ route('terms') }}">Terms</a>
            </div>
        </div>

        <div class="footer-legal">
            <p>© {{ date('Y') }} Dunia Media · RA Media Relations Sdn Bhd (948761-W). All rights reserved.</p>
            <p>Inventory, rates, client relationships and availability shown on this platform are subject to verification with media owners and partners.</p>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
