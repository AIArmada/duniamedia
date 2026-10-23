<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0657d9"><meta name="description" content="Dunia Media by RA Media. Plan, discover and connect media, creators, branding, AI and technology solutions.">
<title>{{ $pageTitle ?? 'Dunia Media — All Media. Bigger Possibilities.' }}</title>
<link rel="icon" href="{{ asset('assets/icons/favicon.ico') }}" sizes="any"><link rel="icon" href="{{ asset('assets/icons/favicon.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">@livewireStyles
</head>
<body>
<header class="site-header" id="site-header">
 <div class="container header-inner">
  <a href="{{ route('home') }}" class="brand" aria-label="Dunia Media homepage"><img src="{{ asset('assets/logo.svg') }}" alt="Dunia Media" width="208" height="45"><span>ALL MEDIA. BIGGER POSSIBILITIES.</span></a>
  <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>
  <nav class="nav" id="primary-nav" aria-label="Primary navigation">
   <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
   <a href="{{ route('media.index') }}" class="{{ request()->routeIs('media.*') ? 'active' : '' }}">Media Buy</a>
   <a href="{{ route('services') }}">Services</a>
   <a href="{{ route('creators.index') }}">Creators</a>
   <a href="{{ route('solutions') }}">Solutions</a>
   <a href="{{ route('academy') }}">Academy</a>
   <a href="{{ route('portfolio') }}">Portfolio</a>
   <a href="{{ route('insights') }}">Insights</a>
   <a href="{{ route('about') }}">About</a>
   <a href="{{ route('contact') }}" class="nav-contact">Let's Talk</a>
  </nav>
  <a href="{{ route('quote.builder') }}" class="btn btn-primary header-cta">Request Proposal <span>↗</span></a>
 </div>
</header>
<main id="main">@if(isset($slot)) {{ $slot }} @endif @yield('content')</main>
<footer class="footer">
 <div class="container footer-cta"><div><span class="eyebrow pale">LET'S MAKE IT HAPPEN</span><h2>Your next big campaign<br>starts with a conversation.</h2><p>Tell us the outcome you're after. We'll help connect the right channels, content and technology.</p></div><div class="actions"><a href="{{ route('advisor') }}" class="btn btn-white">Plan My Campaign ↗</a><a href="{{ route('contact') }}" class="btn btn-ghost-light">Talk to Our Team</a></div></div>
 <div class="container footer-links">
  <div class="footer-brand"><img src="{{ asset('assets/logo.svg') }}" alt="Dunia Media"><p>Media. Creativity. Technology. AI.<br>All connected through one platform.</p><p class="byline">A digital brand initiative by RA Media.</p></div>
  <div><strong>Explore</strong><a href="{{ route('media.index') }}">Media Marketplace</a><a href="{{ route('creators.index') }}">Creator Network</a><a href="{{ route('services') }}">Our Services</a><a href="{{ route('portfolio') }}">Portfolio</a></div>
  <div><strong>Solutions</strong><a href="{{ route('solutions') }}">Brand & Creative</a><a href="{{ route('advisor') }}">Media Advisor</a><a href="{{ route('assets.join') }}">Monetise Your Space</a><a href="{{ route('academy') }}">Training & AI</a></div>
  <div><strong>Company</strong><a href="{{ route('about') }}">About RA Media</a><a href="{{ route('insights') }}">Insights</a><a href="{{ route('privacy') }}">Privacy</a><a href="{{ route('terms') }}">Terms</a></div>
  <div><strong>Connect</strong><a href="{{ route('contact') }}">Contact Us</a><a href="{{ route('creators.join') }}">Join as Creator</a><a href="{{ route('assets.join') }}">List Your Space</a></div>
 </div>
 <div class="container legal"><span>© {{ date('Y') }} Dunia Media · RA Media. All rights reserved.</span><span>Concept-stage platform. Inventory, clients and pricing subject to verification.</span></div>
</footer>
<script src="{{ asset('assets/js/site.js') }}" defer></script>@livewireScripts
</body></html>
