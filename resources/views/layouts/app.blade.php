<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0657d9"><meta name="description" content="{{ $metaDescription ?? 'Dunia Media is a digital brand initiative by RA Media Relations Sdn Bhd (948761-W). Plan, discover and connect media, creators, branding, AI and technology solutions.' }}">
<title>{{ $pageTitle ?? 'Dunia Media — All Media. Bigger Possibilities.' }}</title>
<link rel="icon" href="{{ asset('assets/icons/favicon.ico') }}" sizes="any"><link rel="icon" href="{{ asset('assets/icons/favicon.svg') }}" type="image/svg+xml">
@if (request()->routeIs('home'))
<link rel="preload" as="image" href="{{ \App\Support\MediaImage::url('assets/img/hero-media-city.webp') }}" media="(min-width: 701px)" fetchpriority="high">
<link rel="preload" as="image" href="{{ \App\Support\MediaImage::url('assets/img/hero-media-mobile.webp') }}" media="(max-width: 700px)" fetchpriority="high">
@endif
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
</head>
<body @class(['home-page' => request()->routeIs('home')])>
<header class="site-header" id="site-header">
 <div class="container header-inner">
  <a href="{{ route('home') }}" class="brand brand-lockup" aria-label="Dunia Media — All Media. Bigger Possibilities.">
   <svg class="brand__mark" viewBox="0 0 38 38" aria-hidden="true" focusable="false">
    <path class="brand__mark-body" d="M3.5 4h14.1c9.2 0 15.5 6.1 15.5 15s-6.3 15-15.5 15H3.5V4Z"></path>
    <path class="brand__mark-cutout" d="M10.5 11h7.1c5.1 0 8.1 3 8.1 8s-3 8-8.1 8h-7.1V11Z"></path>
    <path class="brand__mark-play" d="m14 13.7 10.2 5.3L14 24.3V13.7Z"></path>
   </svg>
   <span class="brand__copy"><strong class="brand__name">Dunia<span>Media</span></strong><small class="brand__tagline">ALL MEDIA. BIGGER POSSIBILITIES.</small></span>
  </a>
  <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="primary-nav" data-menu-toggle><span></span><span></span><span></span></button>
  <nav class="nav" id="primary-nav" aria-label="Primary navigation">
   <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
   <a href="{{ route('media.index') }}" class="{{ request()->routeIs('media.*') ? 'active' : '' }}">Media</a>
   <a href="{{ route('solutions') }}" class="{{ request()->routeIs('solutions') ? 'active' : '' }}">Solutions</a>
   <a href="{{ route('creators.index') }}" class="{{ request()->routeIs('creators.*') ? 'active' : '' }}">Creators</a>
   <a href="{{ route('academy') }}" class="{{ request()->routeIs('academy') ? 'active' : '' }}">Training &amp; Events</a>
   <a href="{{ route('work.index') }}" class="{{ request()->routeIs('work.*') ? 'active' : '' }}">Our Work</a>
   <a href="{{ route('insights') }}" class="{{ request()->routeIs('insights*') ? 'active' : '' }}">Insights</a>
   <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
  </nav>
  <div class="header-tools">
   <a class="header-search" href="{{ route('media.index') }}" aria-label="Search media">
    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.6"></circle><path d="m16 16 4 4"></path></svg>
   </a>
   <span class="locale-chip" aria-label="Current region: Malaysia">MY <span aria-hidden="true">⌄</span></span>
   <a href="{{ route('quote.builder') }}" class="btn btn-primary header-cta">Get a Quote <span>→</span></a>
  </div>
 </div>
</header>
<main id="main">@if(isset($slot)) {{ $slot }} @endif @yield('content')</main>
<footer class="footer">
 <div class="container footer-cta"><div><span class="eyebrow pale">LET'S MAKE IT HAPPEN</span><h2>Your next big campaign<br>starts with a conversation.</h2><p>Tell us the outcome you're after. We'll help connect the right channels, content and technology.</p></div><div class="actions"><a href="{{ route('advisor') }}" class="btn btn-white">Plan My Campaign ↗</a><a href="{{ route('contact') }}" class="btn btn-ghost-light">Talk to Our Team</a></div></div>
 <div class="container footer-links">
  <div class="footer-brand">
   <div class="brand-lockup brand-lockup--footer" aria-label="Dunia Media">
    <svg class="brand__mark" viewBox="0 0 38 38" aria-hidden="true" focusable="false">
     <path class="brand__mark-body" d="M3.5 4h14.1c9.2 0 15.5 6.1 15.5 15s-6.3 15-15.5 15H3.5V4Z"></path>
     <path class="brand__mark-cutout" d="M10.5 11h7.1c5.1 0 8.1 3 8.1 8s-3 8-8.1 8h-7.1V11Z"></path>
     <path class="brand__mark-play" d="m14 13.7 10.2 5.3L14 24.3V13.7Z"></path>
    </svg>
    <span class="brand__copy"><strong class="brand__name">Dunia<span>Media</span></strong><small class="brand__tagline">ALL MEDIA. BIGGER POSSIBILITIES.</small></span>
   </div>
   <p>Media. Creativity. Technology. AI.<br>All connected through one platform.</p><p class="byline">A digital brand initiative by RA Media Relations Sdn Bhd (948761-W).</p>
  </div>
  <div><strong>Explore</strong><a href="{{ route('media.index') }}">Media Marketplace</a><a href="{{ route('creators.index') }}">Creator Network</a><a href="{{ route('services') }}">Our Services</a><a href="{{ route('work.index') }}">Our Work</a></div>
  <div><strong>Solutions</strong><a href="{{ route('solutions') }}">Brand & Creative</a><a href="{{ route('advisor') }}">Media Advisor</a><a href="{{ route('assets.join') }}">Monetise Your Space</a><a href="{{ route('academy') }}">Training & AI</a></div>
  <div><strong>Company</strong><a href="{{ route('about') }}">About RA Media</a><a href="{{ route('insights') }}">Insights</a><a href="{{ route('privacy') }}">Privacy</a><a href="{{ route('terms') }}">Terms</a></div>
  <div><strong>Connect</strong><a href="{{ route('contact') }}">Contact Us</a><a href="{{ route('about') }}#company-information">Kuala Lumpur office</a><a href="{{ route('creators.join') }}">Join as Creator</a><a href="{{ route('assets.join') }}">List Your Space</a></div>
 </div>
 <div class="container legal"><span>© {{ date('Y') }} Dunia Media · RA Media Relations Sdn Bhd (948761-W). All rights reserved.</span><span>Concept-stage platform. Inventory, clients and pricing subject to verification.</span></div>
</footer>
@livewireScripts
</body></html>
