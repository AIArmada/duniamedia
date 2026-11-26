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
@php
    $ldContext = '@'.'context';
    $ldType = '@'.'type';
    $ldSchema = [
        $ldContext => 'https://schema.org',
        $ldType => 'Organization',
        'name' => 'Dunia Media',
        'url' => url('/'),
        'logo' => asset('assets/brand/logo-horizontal.svg'),
        'description' => 'Dunia Media connects media, creative, technology and creators as one connected campaign. A digital brand initiative by RA Media Relations Sdn Bhd.',
        'parentOrganization' => [
            $ldType => 'Organization',
            'name' => 'RA Media Relations Sdn Bhd',
            'identifier' => '948761-W',
        ],
        'address' => [
            $ldType => 'PostalAddress',
            'streetAddress' => '19B, Jalan Wan Kadir 2, Taman Tun Dr Ismail',
            'addressLocality' => 'Kuala Lumpur',
            'postalCode' => '60000',
            'addressCountry' => 'MY',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($ldSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if (request()->routeIs('contact'))
<link rel="preconnect" href="https://challenges.cloudflare.com">
@endif
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
</head>
<body @class([
    'has-cover-header' => \App\Shared\Support\PageHero::isMediaLed(),
])>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" data-site-header>
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Dunia Media home">
            <img class="brand__logo brand__logo--color" src="{{ asset('assets/brand/logo-horizontal.svg') }}" width="184" height="51" alt="Dunia Media" decoding="async">
            <img class="brand__logo brand__logo--reversed" src="{{ asset('assets/brand/logo-horizontal-reversed.svg') }}" width="184" height="51" alt="Dunia Media" decoding="async">
        </a>

        <nav class="primary-nav" aria-label="Primary navigation">
            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('media.*') || request()->routeIs('rates.*') ? 'is-active' : '' }}">
                    Media
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('media.index') }}"><span class="nav-panel__label"><x-dm-icon name="layers" :size="17"/>Browse media opportunities</span> <span>Filter by platform, medium, owner, format and location</span></a>
                    <a href="{{ route('rates.index') }}"><span class="nav-panel__label"><x-dm-icon name="chart" :size="17"/>Media Rate Exchange</span> <span>Search every extracted rate line by owner, channel and budget</span></a>
                    <a href="{{ route('media.owners') }}"><span class="nav-panel__label"><x-dm-icon name="building" :size="17"/>Media owners</span> <span>Broadcasters, publishers, out-of-home and specialist channels</span></a>
                    <a href="{{ route('assets.join') }}"><span class="nav-panel__label"><x-dm-icon name="building" :size="17"/>List your space</span> <span>For media owners, operators and property managers</span></a>
                    <a href="{{ route('advisor') }}"><span class="nav-panel__label"><x-dm-icon name="compass" :size="17"/>Media Strategy Studio</span> <span>Start with your objective, not the inventory</span></a>
                </div>
            </details>

            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('services', 'solutions') ? 'is-active' : '' }}">
                    Solutions
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('solutions') }}"><span class="nav-panel__label"><x-dm-icon name="spark" :size="17"/>Connected campaign system</span> <span>Understand, create, place, engage, convert, learn</span></a>
                    <a href="{{ route('services') }}#planning"><span class="nav-panel__label"><x-dm-icon name="compass" :size="17"/>Planning &amp; placement</span> <span>Strategy, buying, negotiation and scheduling</span></a>
                    <a href="{{ route('services') }}#creative"><span class="nav-panel__label"><x-dm-icon name="camera" :size="17"/>Content &amp; production</span> <span>Film, audio, design and editorial</span></a>
                    <a href="{{ route('services') }}#technology"><span class="nav-panel__label"><x-dm-icon name="code" :size="17"/>Digital systems &amp; AI</span> <span>Websites, CRM, automation and practical AI</span></a>
                    <a href="{{ route('services') }}#measurement"><span class="nav-panel__label"><x-dm-icon name="chart" :size="17"/>Campaign measurement</span> <span>Tracking, dashboards and useful reporting</span></a>
                    <a href="{{ route('services') }}"><span class="nav-panel__label"><x-dm-icon name="briefcase" :size="17"/>All services</span> <span>The full capability overview</span></a>
                </div>
            </details>

            <details class="nav-group">
                <summary class="nav-link {{ request()->routeIs('creators.*') ? 'is-active' : '' }}">
                    Creators
                    <svg viewBox="0 0 12 8" aria-hidden="true"><path d="m1 1.5 5 5 5-5"/></svg>
                </summary>
                <div class="nav-panel">
                    <a href="{{ route('creators.index') }}"><span class="nav-panel__label"><x-dm-icon name="users" :size="17"/>Find creators</span> <span>Explore the network by niche and platform</span></a>
                    <a href="{{ route('creators.join') }}"><span class="nav-panel__label"><x-dm-icon name="creators" :size="17"/>Join the network</span> <span>For independent creators and talent</span></a>
                </div>
            </details>

            <a class="nav-link {{ request()->routeIs('plans.*') ? 'is-active' : '' }}" href="{{ route('plans.index') }}">Plans</a>
            <a class="nav-link {{ request()->routeIs('work.*') ? 'is-active' : '' }}" href="{{ route('work.index') }}">Work</a>
            <a class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">About</a>
        </nav>

        <div class="header-actions">
            <a class="header-search" href="{{ route('search') }}" aria-label="Search Dunia Media">
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
                <a href="{{ route('media.index') }}">Browse media opportunities</a>
                <a href="{{ route('media.owners') }}">Media owners</a>
                <a href="{{ route('assets.join') }}">List your space</a>
                <a href="{{ route('advisor') }}">Media Strategy Studio</a>
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
        <a class="mobile-nav__direct" href="{{ route('plans.index') }}">Campaign plans</a>
        <a class="mobile-nav__direct" href="{{ route('work.index') }}">Work</a>
        <a class="mobile-nav__direct" href="{{ route('about') }}">About</a>
        <div class="mobile-nav__actions">
            <a class="button button--primary" href="{{ route('advisor') }}">Plan a Campaign <span aria-hidden="true">↗</span></a>
            <a class="button button--secondary" href="{{ route('contact') }}">Talk to the team</a>
        </div>
    </div>
</header>

@if (session('plan_added'))
    <div class="plan-flash">
        <div class="container plan-flash__inner">
            <span>Added <strong>{{ session('plan_added.name') }}</strong> to your plan.</span>
            <a href="{{ route('plans.show', session('plan_added.instance')) }}">
                Review plan · {{ session('plan_added.count') }} {{ session('plan_added.count') === 1 ? 'item' : 'items' }} · RM {{ number_format(session('plan_added.total'), 2) }} ↗
            </a>
        </div>
    </div>
@endif

<main id="main">
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        @unless(request()->routeIs('home', 'media.index', 'media.owners', 'media.owner', 'media.channel', 'media.channel.show', 'creators.index', 'creators.show', 'solutions', 'plans.*', 'advisor', 'advisor.*', 'quote.*', 'rates.*'))
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

        @endunless
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('assets/brand/logo-horizontal-reversed.svg') }}" width="176" height="49" alt="Dunia Media" loading="lazy" decoding="async">
                <p>Media, creativity, technology and AI — connected through one platform. A digital brand initiative by RA Media Relations Sdn Bhd (948761-W).</p>
            </div>
            <div class="footer-col">
                <strong>Explore</strong>
                <a href="{{ route('media.index') }}">Media opportunities</a>
                <a href="{{ route('media.owners') }}">Media owners</a>
                <a href="{{ route('assets.join') }}">List your space</a>
                <a href="{{ route('creators.index') }}">Creator network</a>
                <a href="{{ route('plans.index') }}">Campaign plans</a>
                <a href="{{ route('work.index') }}">Our work</a>
            </div>
            <div class="footer-col">
                <strong>Solutions</strong>
                <a href="{{ route('solutions') }}">Connected campaigns</a>
                <a href="{{ route('services') }}">All services</a>
                <a href="{{ route('advisor') }}">Media Strategy Studio</a>
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
            <p>Inventory, rates, client relationships and availability shown on this platform are subject to verification with media owners and partners. Campaign footage and client marks are drawn from the RA Media archive where shown.</p>
        </div>
    </div>
</footer>

@livewireScripts
@fluxScripts
@signalsTracker
</body>
</html>
