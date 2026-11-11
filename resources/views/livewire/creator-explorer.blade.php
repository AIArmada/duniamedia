<div>
    <x-page-hero
        eyebrow="Dunia Media creator network"
        title="Reach communities that <span class='editorial'>actually care</span>."
        lede="For brands, the right creator is not always the one with the biggest following. For creators, the right partnership is more than a one-off post. We connect both sides with a focus on fit, craft and useful outcomes."
    />

    <section class="section">
        <div class="container">
            <div class="creator-paths">
                <a class="creator-path" href="{{ route('contact') }}">
                    <img src="{{ asset('assets/img/creators.webp') }}" alt="Creator filming a product story" loading="lazy" decoding="async">
                    <span class="creator-path__body">
                        <span class="eyebrow eyebrow--pale">For brands</span>
                        <h2>Find voices that fit the brief.</h2>
                        <p>Niche matching, clear briefing, creative support and measurement — built around the audience and the outcome.</p>
                        <span>Talk about a creator campaign <span aria-hidden="true">↗</span></span>
                    </span>
                </a>
                <a class="creator-path" href="{{ route('creators.join') }}">
                    <img src="{{ asset('assets/img/creator-feature.webp') }}" alt="Creator working with a camera and content setup" loading="lazy" decoding="async">
                    <span class="creator-path__body">
                        <span class="eyebrow eyebrow--pale">For creators</span>
                        <h2>Your craft deserves the right opportunities.</h2>
                        <p>Join the network to explore brand collaborations, production support, creative development and digital tools.</p>
                        <span>Join the network <span aria-hidden="true">↗</span></span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section class="section section--cream" aria-labelledby="directory-title">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Creator discovery</span>
                    <h2 id="directory-title" class="section-title">Find the right voices.</h2>
                    <p>Profiles appear only after onboarding checks and consent. No follower counts or performance claims are published without the creator's approval.</p>
                </div>
            </div>

            @if ($creators->isEmpty())
                <div class="empty-state" style="text-align:left">
                    <span class="eyebrow">Profiles are being verified</span>
                    <h3>The creator directory is in preparation.</h3>
                    <p style="margin-left:0">We are onboarding creators carefully rather than filling the page with invented faces and numbers. In the meantime, tell us the audience, niche and format you need — we will suggest a route.</p>
                    <div class="empty-state__actions" style="justify-content:flex-start">
                        <a class="button button--primary" href="{{ route('creators.join') }}">Join as a creator <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('contact') }}">Talk about a creator campaign</a>
                    </div>
                </div>

                <div class="topic-grid" style="margin-top:var(--grid-gap)">
                    <article class="topic"><h3>Niche fit</h3><p>Travel, food, family, technology, finance, lifestyle and more — matched to the audience rather than the follower count.</p></article>
                    <article class="topic"><h3>Production support</h3><p>Creative direction, scripting, filming and editing for creator-led campaigns that need a consistent brand finish.</p></article>
                    <article class="topic"><h3>Measurement</h3><p>Clear objectives, agreed deliverables and reporting that separates reach from meaningful response.</p></article>
                </div>
            @else
                <div class="filter-horizontal" style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:0.6rem;margin-bottom:1.4rem">
                    <label class="field"><span class="sr-only">Search creators</span><input type="search" wire:model.live.debounce.350ms="search" placeholder="Search creator profiles…"></label>
                    <label class="field"><span class="sr-only">Niche</span><select wire:model.live="niche"><option value="all">All niches</option>@foreach (['Travel', 'Food', 'Technology', 'Family'] as $niche)<option>{{ $niche }}</option>@endforeach</select></label>
                    <label class="field"><span class="sr-only">Platform</span><select wire:model.live="platform"><option value="all">All platforms</option>@foreach (['TikTok', 'Instagram', 'YouTube'] as $platform)<option>{{ $platform }}</option>@endforeach</select></label>
                </div>

                <div class="media-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
                    @foreach ($creators as $creator)
                        <article class="creator-card" wire:key="creator-{{ $creator->id }}">
                            <div class="creator-card__image">
                                <img src="{{ \App\Support\MediaImage::url($creator->image) }}" alt="{{ $creator->name }}" loading="lazy" decoding="async">
                            </div>
                            <div class="creator-card__body">
                                <div class="creator-card__meta">
                                    <span class="tag tag--verified">Verified</span>
                                    <span class="tag">{{ $creator->platform }}</span>
                                </div>
                                <h3>{{ $creator->name }}</h3>
                                <p>{{ $creator->bio }}</p>
                                <a href="{{ route('contact') }}" class="text-link" style="margin-top:0.6rem">Discuss a collaboration <span aria-hidden="true">↗</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="pagination-wrap">{{ $creators->links('pagination.brand', ['livewire' => true]) }}</div>
            @endif
        </div>
    </section>
</div>
