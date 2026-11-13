@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Media ecosystem"
        title="One vocabulary from <span class='editorial'>location</span> to case study."
        lede="Dunia Media separates who controls the media, what the media is, where it lives and how it is sold. That separation is what makes a plan comparable, bookable and reportable."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('media.index') }}">Browse media</a>
            <a class="button button--secondary" href="{{ route('media.owners') }}">Browse owners</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Four words people mix up</span>
                    <h2 class="section-title">Platform, medium, channel, format.</h2>
                    <p>These are not interchangeable. A plan becomes comparable when each one is named correctly.</p>
                </div>
            </div>
            <div class="topic-grid">
                <article class="topic"><span class="tag tag--blush">Platform</span><h3>Traditional or digital</h3><p>The broad delivery world. Traditional covers broadcast, print, outdoor and cinema. Digital covers websites, apps, social, OTT and digital screens.</p></article>
                <article class="topic"><span class="tag tag--blush">Medium</span><h3>The type of media</h3><p>Television, radio, print, out-of-home, DOOH, website, social media, creator, cinema or place-based. A medium describes what kind of media it is, not who sells it.</p></article>
                <article class="topic"><span class="tag tag--blush">Channel</span><h3>The specific channel or brand</h3><p>TV3, TV9, Hot FM, 988, BFM, The Star, a YouTube channel or a named billboard network. A channel is the distribution channel a buyer actually appears on.</p></article>
                <article class="topic"><span class="tag tag--blush">Format</span><h3>The creative shape</h3><p>A 30-second TV spot, a 15-second radio spot, a billboard face, a lift screen loop, an Instagram Story or a sponsored article. The format tells the creative team what to produce.</p></article>
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">The chain</span>
                    <h2 class="section-title">How an opportunity is assembled.</h2>
                    <p>A location becomes sellable inventory only after rights, structure, asset, surface, placement and commercial terms are clear.</p>
                </div>
            </div>

            <div class="topic-grid">
                <article class="topic"><span class="tag tag--blush">01 · Location</span><h3>Where the media lives</h3><p>A <strong>venue</strong> has an audience, such as a mall, restaurant or office. A <strong>site</strong> is a specific piece of land, roadside or structure. A <strong>host structure</strong> is the pillar, wall, lift, table or vehicle that carries the asset.</p></article>
                <article class="topic"><span class="tag tag--blush">02 · Rights</span><h3>Who can sell it</h3><p>A <strong>rights holder</strong> grants the right to operate or sell. A <strong>media owner</strong> controls the property. A <strong>media operator</strong> runs it. A <strong>sales house</strong> represents it. These are separate roles, even when one company holds several.</p></article>
                <article class="topic"><span class="tag tag--blush">03 · Property</span><h3>The channel or publication</h3><p>A <strong>media property</strong> is the channel, station, publication, website, social account or physical network. A <strong>media asset</strong> is the physical or digital object, such as a billboard, screen, poster frame or website container.</p></article>
                <article class="topic"><span class="tag tag--blush">04 · Surface</span><h3>The sellable face</h3><p>An <strong>asset surface</strong> is the face, panel, loop or slot that carries creative. A <strong>format</strong> describes the creative shape, such as a 30-second spot, billboard face or Instagram Story.</p></article>
                <article class="topic"><span class="tag tag--blush">05 · Placement</span><h3>The bookable opportunity</h3><p>A <strong>placement</strong> is the specific opportunity a buyer can book. A <strong>media offering</strong> packages one or more placements with a commercial unit and a rate.</p></article>
                <article class="topic"><span class="tag tag--blush">06 · Inventory</span><h3>Capacity and timing</h3><p>An <strong>inventory pool</strong> defines the capacity model. <strong>Inventory windows</strong> hold dates, quantities and reserved totals. <strong>Holds</strong> protect a request until it becomes a quote or booking.</p></article>
                <article class="topic"><span class="tag tag--blush">07 · Plan</span><h3>The campaign plan</h3><p>A <strong>campaign plan</strong> is a saved working mix of offerings, quantities, package savings and brief metadata. It is not a booking. It becomes a <strong>quote request</strong> when the buyer is ready.</p></article>
                <article class="topic"><span class="tag tag--blush">08 · Campaign</span><h3>Execution and proof</h3><p>A <strong>quote</strong> becomes a <strong>booking</strong> after confirmation. A <strong>campaign</strong> groups the flights, placements, creative, delivery proof and performance metrics. A <strong>case study</strong> turns verified outcomes into public proof.</p></article>
            </div>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">The players</span>
                    <h2 class="section-title">Who does what.</h2>
                </div>
            </div>
            <div class="topic-grid">
                <article class="topic"><h3>Media owner</h3><p>Owns or controls the media property. Approves inventory, formats, creative and final availability.</p></article>
                <article class="topic"><h3>Media operator</h3><p>Runs the day-to-day operation of the asset or network, including installation, playback and maintenance.</p></article>
                <article class="topic"><h3>Rights holder</h3><p>Grants the concession or licence that lets another party operate or sell the media.</p></article>
                <article class="topic"><h3>Sales house</h3><p>Represents inventory commercially. May sell one owner or a portfolio of owners.</p></article>
                <article class="topic"><h3>Creator</h3><p>Produces audience-led content on a social property. A creator profile is not a media owner, even when the creator sells sponsored placements.</p></article>
                <article class="topic"><h3>Buyer / client</h3><p>The brand, agency or organisation commissioning the campaign. The client pays; the advertiser or brand is what the audience sees.</p></article>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta-panel__inner">
                <div>
                    <span class="eyebrow">Use the vocabulary</span>
                    <h2 class="section-title">Start with a medium, an owner or an outcome.</h2>
                    <p style="max-width:560px;margin-top:.7rem;color:rgba(255,248,247,.66)">Browse the catalogue by medium and owner, or give the advisor the objective, budget and timing and let it suggest a direction.</p>
                </div>
                <div class="cta-panel__actions">
                    <a class="button button--on-dark" href="{{ route('media.index') }}">Explore media <span aria-hidden="true">↗</span></a>
                    <a class="button button--outline-light" href="{{ route('advisor') }}">Open the advisor</a>
                </div>
            </div>
        </div>
    </section>
@endsection
