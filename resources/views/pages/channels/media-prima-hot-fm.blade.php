@extends('layouts.app')

@section('content')
    @php
        $zoomerz = collect($sections)->filter(fn (array $section): bool => ($section['product']['slug'] ?? null) === 'hot-fm-zoomerz-on-ground')->keyBy('key');
        $investment = collect($zoomerz->get('zoomerz-sponsorship-media-investment')['entries'] ?? [])->first();
        $production = $zoomerz->get('zoomerz-sponsorship-production-cost');
        $outstation = $zoomerz->get('hot-fm-zoomerz-sponsorship');
        $mediaValue = $zoomerz->get('zoomerz-sponsorship-media-value');
        $featuredKeys = ['zoomerz-sponsorship-media-investment', 'zoomerz-sponsorship-production-cost', 'hot-fm-zoomerz-sponsorship', 'zoomerz-sponsorship-media-value'];
        $otherSections = collect($sections)->reject(fn (array $section): bool => ($section['product']['slug'] ?? null) === 'hot-fm-zoomerz-on-ground' && in_array($section['key'], $featuredKeys, true));
        $money = static fn ($value): string => $value === null ? 'On request' : 'RM '.number_format((float) $value, fmod((float) $value, 1.0) === 0.0 ? 0 : 2);
        $logo = 'assets/partners/mp-hotfm-v2.png';
        $ownerSlug = 'media-prima';
        $radioSiblings = collect(\App\Features\Catalog\Support\MediaDirectory::owner($ownerSlug)['channels'] ?? [])
            ->where('category', 'Radio')->where('slug', '!=', 'hot-fm')->values();
        $profile = json_decode(file_get_contents(resource_path('data/hot-fm-profile.json')), true, flags: JSON_THROW_ON_ERROR);
        $programmes = $profile['programmes'];
        $formats = [
            ['icon' => 'radio', 'title' => 'Radio', 'body' => 'On-air promotions and live crosses.'],
            ['icon' => 'target', 'title' => 'On-ground', 'body' => 'Roving team activations and product sampling.'],
            ['icon' => 'digital', 'title' => 'Social', 'body' => 'Digital highlights and social amplification.'],
            ['icon' => 'users', 'title' => 'Talent-led', 'body' => 'Personality-led station campaigns and conversations.'],
        ];
    @endphp
    <div class="hotfm-page">
        <nav class="hotfm-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.owners') }}">Media owners</a><span aria-hidden="true">/</span>
            <a href="{{ route('media.owner', $ownerSlug) }}">Media Prima</a><span aria-hidden="true">/</span>
            <strong aria-current="page">Hot FM</strong>
        </nav>

        <header class="hotfm-hero">
            <div class="hotfm-hero__copy">
                <div class="hotfm-hero__brand"><img src="{{ \App\Features\Catalog\Support\MediaImage::url($logo) }}" alt="Hot FM" width="240" height="100" fetchpriority="high"><span>Media Prima Audio</span></div>
                <h1>Get heard. <em>Get closer.</em></h1>
                <p>Connect your brand with Malaysia’s Malay-language radio audiences through familiar voices, entertainment and real community experiences.</p>
                <div class="hotfm-hero__actions">
                    <a class="hotfm-primary" href="{{ route('contact') }}">Discuss your campaign <span aria-hidden="true">→</span></a>
                    <a class="hotfm-secondary" href="#hotfm-rates">Explore rates &amp; packages</a>
                </div>
                <a class="hotfm-detail-link" href="#hotfm-programmes-title"><x-dm-icon name="users" :size="18"/> Meet the presenters ↓</a>
            </div>
            <a class="hotfm-hero__photo" href="#hotfm-programme-bekpes-hot" aria-label="Explore Bekpes Hot on DuniaMedia">
                <img src="{{ $programmes[0]['image'] }}" alt="Official Bekpes Hot programme artwork" width="1000" height="525" fetchpriority="high" referrerpolicy="no-referrer">
                <div class="hotfm-hero__programme"><span>Weekday breakfast · 6am–10am</span><strong>Bekpes Hot</strong><span>{{ $programmes[0]['hosts'] }}</span><small>Explore programme ↓</small></div>
            </a>
        </header>

        <section class="hotfm-reach" aria-label="Hot FM audience and capabilities">
            <div class="hotfm-audience">
                <x-dm-icon name="radio" :size="42"/>
                <div><span>Weekly radio listenership</span><strong>{{ $profile['audience']['value'] }}</strong><p>{{ $profile['audience']['method'] }} <span class="hotfm-attribution">Source: Media Prima Audio.</span></p></div>
            </div>
            <div class="hotfm-formats">
                @foreach ($formats as $format)
                    <article><div class="hotfm-format-icon"><x-dm-icon :name="$format['icon']" :size="25"/></div><div><h2>{{ $format['title'] }}</h2><p>{{ $format['body'] }}</p></div></article>
                @endforeach
            </div>
        </section>

        <section class="hotfm-discovery" aria-labelledby="hotfm-programmes-title">
            <div class="hotfm-programmes">
                <div class="hotfm-section-heading"><div><span class="hotfm-eyebrow">On air</span><h2 id="hotfm-programmes-title">Meet the voices behind Hot FM</h2><p>Explore the shows, personalities and possible brand integrations.</p></div><a class="hotfm-detail-link" href="#hotfm-schedule">Weekday schedule ↓</a></div>
                <div class="hotfm-programme-grid">
                    @foreach ($programmes as $programme)
                        <article class="hotfm-programme" id="hotfm-programme-{{ \Illuminate\Support\Str::slug($programme['title']) }}">
                            <div class="hotfm-programme__image"><img src="{{ $programme['image'] }}" alt="Official {{ $programme['title'] }} artwork" width="1000" height="525" loading="lazy" decoding="async" referrerpolicy="no-referrer"></div>
                            <div class="hotfm-programme__body">
                                <span class="hotfm-programme__time">{{ $programme['time'] }}</span>
                                <h3>{{ $programme['title'] }}</h3>
                                <strong>{{ $programme['hosts'] }}</strong>
                                <p>{{ $programme['description'] }}</p>
                                <div class="hotfm-programme__opportunity"><span>Campaign idea</span>{{ $programme['opportunity'] }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p class="hotfm-editorial-note">Programme artwork and listings: Hot FM. Host bookings, endorsements and specific integrations require broadcaster approval and a tailored quotation. Listings reviewed {{ $profile['reviewed_on'] }}.</p>
            </div>

            <aside class="hotfm-sidebar" aria-label="Programme schedule and digital platforms">
                <section class="hotfm-schedule" id="hotfm-schedule">
                    <h2><x-dm-icon name="calendar" :size="23"/> Weekday programmes</h2>
                    <p>Monday–Friday · Malaysia time</p>
                    @foreach ($programmes as $programme)
                        <a href="#hotfm-programme-{{ \Illuminate\Support\Str::slug($programme['title']) }}"><span>{{ $programme['time'] }}</span><strong>{{ $programme['title'] }} →</strong></a>
                    @endforeach
                    <p class="hotfm-schedule__note">Selected weekday shows. Programme times are subject to change.</p>
                </section>
                <section class="hotfm-digital">
                    <h2><x-dm-icon name="digital" :size="23"/> Beyond the airwaves</h2>
                    @foreach ($profile['digital'] as $platform)
                        <div class="hotfm-digital__item"><strong>{{ $platform['label'] }}</strong><span>{{ $platform['handle'] }}</span></div>
                    @endforeach
                </section>
            </aside>
        </section>

        <section class="hotfm-campaigns" aria-labelledby="hotfm-campaigns-title">
            <div class="hotfm-section-heading"><div><span class="hotfm-eyebrow">Real campaigns</span><h2 id="hotfm-campaigns-title">From airwaves to real life.</h2><p>Examples published by Media Prima Audio, showing how Hot FM connects brands with listeners.</p></div></div>
            <div class="hotfm-campaign-grid">
                @foreach ($profile['campaigns'] as $campaign)
                    <article class="hotfm-campaign">
                        <div class="hotfm-campaign__image"><img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }} — Media Prima Audio campaign photograph" width="1000" height="525" loading="lazy" decoding="async" referrerpolicy="no-referrer"></div>
                        <div class="hotfm-campaign__body"><span>{{ $campaign['date'] }}</span><h3>{{ $campaign['title'] }}</h3><strong>{{ $campaign['metric'] }}</strong><p>{{ $campaign['description'] }}</p><a class="hotfm-detail-link" href="#hotfm-commercial-title">Explore similar activations ↓</a></div>
                    </article>
                @endforeach
            </div>
            <p class="hotfm-editorial-note">Campaigns and results are attributed to Hot FM / Media Prima Audio, not represented as RA Media projects or guaranteed future outcomes.</p>
        </section>

        <section class="hotfm-commercial-intro" aria-labelledby="hotfm-commercial-title">
            <div><span class="hotfm-eyebrow">Advertise with Hot FM</span><h2 id="hotfm-commercial-title">Turn attention into a campaign.</h2><p>Explore on-air spots, programme sponsorship, presenter-led content and on-ground activation opportunities. Ask our team to confirm current availability and rates.</p><a class="hotfm-detail-link" href="{{ route('contact') }}">Enquire about a show or personality →</a></div>
            <div class="hotfm-commercial-intro__action"><x-dm-icon name="layers" :size="35"/><strong>Rates &amp; packages</strong><span>Review the published Zoomerz rate card and add eligible placements to a campaign plan.</span><a href="#hotfm-rates" class="hotfm-primary">View Hot FM rates ↓</a></div>
        </section>

        <section id="hotfm-rates" class="hotfm-opportunities" aria-label="Hot FM rates and packages">
            @if ($investment !== null)
                <article class="hotfm-feature">
                    <span class="hotfm-eyebrow">Featured sponsorship</span>
                    <h2>Hot FM <em>Zoomerz</em></h2>
                    <p>A four-week radio and on-ground sponsorship combining station promotions, live crosses, sampling and car branding.</p>
                    <div class="hotfm-feature__prices">
                        <div><span>Listed sponsorship investment</span><strong>{{ $money($investment['price']) }}</strong><small>{{ $investment['unit'] ?: 'Package' }} · four weeks</small></div>
                        @if ($investment['price_incl_sst'] !== null)
                            <div><span>Including listed SST</span><strong>{{ $money($investment['price_incl_sst']) }}</strong><small>Excludes production costs</small></div>
                        @endif
                    </div>
                    <div class="hotfm-deliverables">
                        @foreach ([['icon' => 'radio', 'value' => '96', 'label' => 'On-air crosses'], ['icon' => 'megaphone', 'value' => '112', 'label' => 'Recorded promos'], ['icon' => 'layers', 'value' => '960', 'label' => 'Product samples'], ['icon' => 'pin', 'value' => '2', 'label' => 'Cars for branding']] as $metric)
                            <div><x-dm-icon :name="$metric['icon']" :size="23"/><strong>{{ $metric['value'] }}</strong><span>{{ $metric['label'] }}</span></div>
                        @endforeach
                    </div>
                    <p class="hotfm-source-note"><x-dm-icon name="document" :size="20"/><span>Historical rate-card terms: {{ $zoomerz->get('zoomerz-sponsorship-media-investment')['product']['validity'] ?? 'Base August 2022; minimum one month' }}. Rates, tax, deliverables and availability require current confirmation. Production is separate.</span></p>
                    <a class="hotfm-detail-link" href="{{ route('offers.show', 'hot-fm-zoomerz-on-ground') }}">View full Zoomerz opportunity →</a>
                </article>

                <div class="hotfm-rate-panel">
                    <header><h2>Build your Zoomerz campaign</h2><p>Choose the sponsorship and applicable production costs. All prices are indicative.</p></header>
                    @foreach ([
                        ['title' => 'Monthly sponsorship', 'entries' => [$investment], 'highlight' => true],
                        ['title' => 'Production costs — separate', 'entries' => $production['entries'] ?? [], 'highlight' => false],
                        ['title' => 'Outstation production — if required', 'entries' => $outstation['entries'] ?? [], 'highlight' => false],
                    ] as $group)
                        @if ($group['entries'] !== [])
                            <section class="hotfm-rate-group">
                                <h3>{{ $group['title'] }}</h3>
                                @foreach ($group['entries'] as $entry)
                                    <div class="hotfm-rate-row @if ($group['highlight']) hotfm-rate-row--featured @endif">
                                        <div><strong>{{ $entry['item'] }}</strong><small>{{ $entry['unit'] ?: 'Unit to confirm' }}</small>@if ($entry['notes'])<details><summary>Rate notes</summary><p>{{ $entry['notes'] }}</p></details>@endif</div>
                                        <div class="hotfm-rate-price"><strong>{{ $money($entry['price']) }}</strong>@if ($entry['price_incl_sst'] !== null)<small>{{ $money($entry['price_incl_sst']) }} incl. SST</small>@endif</div>
                                        @if ($entry['price'] > 0)
                                            <form method="POST" action="{{ route('plans.rate.add', $entry['id']) }}">@csrf<button class="hotfm-primary" type="submit" aria-label="Add {{ $entry['item'] }} to plan">+ Add to plan</button></form>
                                        @else
                                            <a class="hotfm-secondary" href="{{ route('contact') }}">Enquire</a>
                                        @endif
                                    </div>
                                @endforeach
                            </section>
                        @endif
                    @endforeach
                    <p class="hotfm-panel-note">Outstation rates are additional production charges, not daily or weekly sponsorship packages. Quantities and applicable charges are confirmed with our team.</p>
                    <a class="hotfm-detail-link" href="{{ route('plans.index') }}">Review campaign plans →</a>
                </div>
            @else
                <article class="hotfm-feature hotfm-feature--empty"><h2>Plan your Hot FM campaign</h2><p>Ask our team for the latest radio, sponsorship and activation rates.</p><a class="hotfm-primary" href="{{ route('contact') }}">Request current rates →</a></article>
            @endif
        </section>

        @if ($mediaValue)
            <details class="hotfm-value">
                <summary><div class="hotfm-value__icon"><x-dm-icon name="chart" :size="26"/></div><div><strong>Media value — reference only</strong><span>Exposure value is not the amount payable. These entries cannot be added to a campaign plan.</span></div><span class="hotfm-value__expand">View breakdown</span></summary>
                <div class="hotfm-value__body">
                    @foreach ($mediaValue['entries'] as $entry)
                        <div class="hotfm-value-row"><div><strong>{{ $entry['item'] }}</strong><small>{{ $entry['unit'] }}</small>@if ($entry['notes'])<p>{{ $entry['notes'] }}</p>@endif</div><strong>{{ $money($entry['price']) }}</strong></div>
                    @endforeach
                    <p>Historical figures from the source rate card; not independently verified current media value.</p>
                </div>
            </details>
        @endif

        @foreach ($otherSections as $section)
            <x-rate-section :section="$section" :show-channel="false"/>
        @endforeach

        @if ($radioSiblings->isNotEmpty())
            <section class="hotfm-network" aria-labelledby="hotfm-network-title">
                <div><h2 id="hotfm-network-title">The Media Prima radio network</h2><p>Explore other radio brands and campaign opportunities.</p><a class="hotfm-detail-link" href="{{ route('media.owner', $ownerSlug) }}">View full network →</a></div>
                <div class="hotfm-network__brands">
                    @foreach ($radioSiblings as $sibling)
                        @php($siblingLogo = ! empty($sibling['logo']) ? 'assets/partners/'.$sibling['logo'] : null)
                        <a href="{{ route('media.channel', [$ownerSlug, $sibling['slug']]) }}" aria-label="Explore {{ $sibling['name'] }}">
                            @if ($siblingLogo && is_file(public_path($siblingLogo)))
                                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($siblingLogo) }}" alt="{{ $sibling['name'] }}" loading="lazy" decoding="async">
                            @else
                                <strong>{{ $sibling['name'] }}</strong>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="hotfm-cta">
            <div class="hotfm-cta__icon"><x-dm-icon name="chart" :size="35"/></div>
            <div><span>Plan your next campaign</span><h2>Media Strategy Studio</h2></div>
            <p>Combine Hot FM with the right media mix for your audience, objectives and budget.</p>
            <a class="button button--on-dark" href="{{ route('advisor') }}">Open Media Strategy Studio →</a>
        </section>
    </div>
@endsection
