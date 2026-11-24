@extends('layouts.app')

@section('content')
    @php
        $zoomerz = collect($sections)->filter(fn (array $section): bool => ($section['product']['slug'] ?? null) === 'hot-fm-zoomerz-on-ground')->keyBy('key');
        $investment = collect($zoomerz->get('zoomerz-sponsorship-media-investment')['entries'] ?? [])->first();
        $production = $zoomerz->get('zoomerz-sponsorship-production-cost');
        $outstation = $zoomerz->get('hot-fm-zoomerz-sponsorship');
        $mediaValue = $zoomerz->get('zoomerz-sponsorship-media-value');
        $otherSections = collect($sections)->reject(fn (array $section): bool => $zoomerz->has($section['key']));
        $money = static fn ($value): string => $value === null ? 'On request' : 'RM '.number_format((float) $value, fmod((float) $value, 1.0) === 0.0 ? 0 : 2);
        $logo = 'assets/partners/mp-hotfm-v2.png';
        $ownerSlug = 'media-prima';
        $radioSiblings = collect(\App\Features\Catalog\Support\MediaDirectory::owner($ownerSlug)['channels'] ?? [])
            ->where('category', 'Radio')->where('slug', '!=', 'hot-fm')->values();
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
            <div class="hotfm-hero__brand">
                <img src="{{ \App\Features\Catalog\Support\MediaImage::url($logo) }}" alt="Hot FM" width="240" height="100" fetchpriority="high">
            </div>
            <div class="hotfm-hero__copy">
                <span class="hotfm-eyebrow">Hot FM · Media Prima</span>
                <h1>Get heard. <em>Get closer.</em></h1>
                <p>Connect with Malay youth audiences through radio, on-ground activations and digital engagement.</p>
                <div class="hotfm-hero__actions">
                    <a class="hotfm-primary" href="#hotfm-rates"><x-dm-icon name="radio" :size="19"/> Explore rates <span aria-hidden="true">→</span></a>
                    <a class="hotfm-secondary" href="{{ route('advisor') }}"><x-dm-icon name="calendar" :size="19"/> Plan a campaign</a>
                </div>
            </div>
            <svg class="hotfm-wave" viewBox="0 0 560 260" fill="none" aria-hidden="true" focusable="false">
                <defs>
                    <linearGradient id="hotfm-wave-gradient" x1="0" y1="0" x2="560" y2="0" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#fbe0e8" stop-opacity=".15"/><stop offset=".35" stop-color="#ed4b88"/><stop offset=".66" stop-color="#dc003c"/><stop offset="1" stop-color="#ffc481"/>
                    </linearGradient>
                </defs>
                <g stroke="#d80042" stroke-opacity=".16" stroke-width="7" stroke-linecap="round">
                    @foreach ([25, 40, 68, 108, 156, 90, 48, 80, 125, 170, 208, 145, 85, 55, 112, 170, 226, 185, 120, 72, 95, 135, 82] as $index => $height)
                        <path d="M{{ 24 + $index * 23 }} {{ 130 - $height / 2 }}v{{ $height }}"/>
                    @endforeach
                </g>
                <path d="M0 180C70 190 90 65 150 92S235 212 304 121 386 30 440 110 513 156 560 70V153C510 226 465 207 410 150S348 103 300 173 214 230 160 165 70 215 0 205Z" fill="url(#hotfm-wave-gradient)" opacity=".6"/>
                <path d="M0 195C80 205 91 110 157 126S230 235 308 143 392 80 443 142 512 190 560 109" stroke="url(#hotfm-wave-gradient)" stroke-width="26" stroke-linecap="round"/>
                <path d="M0 217C80 215 103 157 160 170S239 235 310 181 390 119 441 177 510 211 560 152" stroke="url(#hotfm-wave-gradient)" stroke-width="11" stroke-opacity=".48" stroke-linecap="round"/>
            </svg>
        </header>

        <section class="hotfm-formats" aria-label="Advertising capabilities">
            @foreach ($formats as $format)
                <article><div class="hotfm-format-icon"><x-dm-icon :name="$format['icon']" :size="29"/></div><div><h2>{{ $format['title'] }}</h2><p>{{ $format['body'] }}</p></div></article>
            @endforeach
        </section>

        <section id="hotfm-rates" class="hotfm-opportunities" aria-label="Hot FM rates and packages">
            @if ($investment)
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
