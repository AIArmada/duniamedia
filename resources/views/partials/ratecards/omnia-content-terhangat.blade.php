@php($rc = require resource_path('data/ratecards/omnia-content-terhangat-2026.php'))
@php($k = static fn (int $value): string => $value >= 1000 ? 'RM '.rtrim(rtrim(number_format($value / 1000, 1), '0'), '.').'k' : 'RM '.number_format($value))
<div class="rc-omnia" data-rc id="rate-card">
    <section class="rc-hero">
        <div class="container rc-hero__grid">
            <div class="rc-hero__copy">
                <span class="eyebrow pale">{{ $rc['eyebrow'] }}</span>
                <h2>Four tiers.<br><em>Your market.</em></h2>
                <p>{{ $rc['subtitle'] }}</p>
                <div class="rc-hero__chips">
                    <span>4 packages</span>
                    <span>3–12 months</span>
                    <span>RM200k–800k</span>
                    <span>12 market tracks</span>
                </div>
                <a class="btn btn-white" href="#rc-packages">Compare packages <span aria-hidden="true">↓</span></a>
            </div>
            <figure class="rc-hero__visual">
                <img src="{{ \App\Support\MediaImage::url('assets/ratecards/img/omnia-content-terhangat-2026.webp') }}" alt="Television production set with a large LED wall">
                <figcaption><span>Media Prima Omnia</span> Content Terhangat 2026</figcaption>
            </figure>
        </div>
    </section>

    <section class="section rc-section" id="rc-packages">
        <div class="container">
            <div class="rc-section__head">
                <div>
                    <span class="eyebrow">The investment</span>
                    <h2>Choose your tier.</h2>
                    <p>Each tier adds reach, content and sponsorship depth. Prices below are media investment; bonus is added media value.</p>
                </div>
                <span class="rc-sst">Excludes 8% SST</span>
            </div>

            <div class="rc-package-grid">
                @foreach($rc['packages'] as $key => $package)
                    <article class="rc-package rc-package--{{ $key }} @if($key === 'gold') rc-package--featured @endif">
                        @if($key === 'gold')<span class="rc-package__flag">Most balanced</span>@endif
                        <header>
                            <span class="rc-package__name">{{ $package['name'] }}</span>
                            <strong>{{ $k($package['price']) }}</strong>
                            <span class="rc-package__months">{{ $package['months'] }}-month campaign</span>
                        </header>
                        <dl>
                            <div><dt>Event bonus</dt><dd>+{{ $k($package['bonus']) }}</dd></div>
                            <div><dt>Total ADvantage value</dt><dd>{{ $k($package['total']) }}</dd></div>
                        </dl>
                        <a class="rc-package__link" href="#rc-markets" data-rc-jump="{{ $key }}">See what’s included <span aria-hidden="true">↗</span></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-soft rc-section rc-markets" id="rc-markets">
        <div class="container">
            <div class="rc-section__head">
                <div>
                    <span class="eyebrow">Tailored by industry</span>
                    <h2>Pick your market.</h2>
                    <p>Twelve tracks across six industries and two language networks. Select a track to see the exact package entitlements.</p>
                </div>
            </div>

            <div class="rc-tabs" data-rc-tabs role="tablist" aria-label="Industry and language">
                @foreach($rc['segments'] as $index => $segment)
                    <button type="button"
                            class="rc-tab @if($index === 0) is-active @endif"
                            data-rc-tab="{{ $segment['key'] }}"
                            role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                        <span>{{ $segment['industry'] }}</span>
                        <small>{{ $segment['language'] }} · {{ $segment['network'] }}</small>
                    </button>
                @endforeach
            </div>

            <div class="rc-panels">
                @foreach($rc['segments'] as $index => $segment)
                    <div class="rc-panel @if($index === 0) is-active @endif" data-rc-panel="{{ $segment['key'] }}" role="tabpanel">
                        <div class="rc-panel__head">
                            <h3>{{ $segment['industry'] }} <span>{{ $segment['language'] }}</span></h3>
                            <span class="rc-network">{{ $segment['network'] }}</span>
                        </div>
                        <div class="rc-tier-grid">
                            @foreach($rc['packages'] as $key => $package)
                                <article class="rc-tier rc-tier--{{ $key }} @if($key === 'gold') rc-tier--featured @endif">
                                    <header>
                                        <span>{{ $package['name'] }}</span>
                                        <strong>{{ $k($package['price']) }}</strong>
                                        <small>{{ $package['months'] }} months · +{{ $k($package['bonus']) }} bonus</small>
                                    </header>
                                    <ul>
                                        @foreach($segment['tiers'][$key] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section rc-section rc-menu">
        <div class="container">
            <div class="rc-section__head">
                <div>
                    <span class="eyebrow">Build your own</span>
                    <h2>À la carte menu.</h2>
                    <p>Selected inventory from the full Content Terhangat menu. Mix and match outside a package.</p>
                </div>
            </div>
            <div class="rc-table-wrap">
                <table class="rc-table">
                    <thead><tr><th>Inventory</th><th>Format</th><th>Qty</th><th>Investment</th></tr></thead>
                    <tbody>
                    @foreach($rc['alacarte'] as $row)
                        <tr>
                            <td data-label="Inventory">{{ $row[0] }}</td>
                            <td data-label="Format">{{ $row[1] }}</td>
                            <td data-label="Qty">{{ $row[2] }}</td>
                            <td data-label="Investment">{{ $k($row[3]) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <p class="rc-fineprint">Selected items shown. Full TV3, TV9 and 8TV inventory is in the source rate card.</p>
        </div>
    </section>

    <section class="section section-soft rc-section rc-terms">
        <div class="container">
            <div class="rc-section__head">
                <div>
                    <span class="eyebrow">Before you book</span>
                    <h2>Good to know.</h2>
                </div>
            </div>
            <div class="rc-terms__grid">
                @foreach($rc['terms'] as $term)
                    @php([$title, $body] = array_pad(explode(':', $term, 2), 2, null))
                    <div class="rc-term">
                        <strong>{{ $title }}</strong>
                        <p>{{ $body ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="rc-cta">
        <div class="container rc-cta__inner">
            <div>
                <span class="eyebrow pale">Ready to plan?</span>
                <h2>Build the campaign around your launch.</h2>
                <p>We will confirm slots, production, availability and the final package before booking.</p>
            </div>
            <div class="rc-cta__actions">
                <a class="btn btn-white" href="{{ route('quote.builder', ['media' => $media->id]) }}">Add to Proposal <span aria-hidden="true">↗</span></a>
                <a class="btn btn-ghost-light" href="{{ route('contact') }}">Talk to a Specialist</a>
            </div>
        </div>
    </section>

    <div class="container rc-source">
        <p>Source: Media Prima Omnia Content Terhangat 2026 · {{ $rc['validity'] }} · Excludes 8% SST and production.</p>
        <a href="{{ route('media.ratecard', [$media->slug, 0]) }}?raw=1" target="_blank" rel="noopener">Download original PDF <span aria-hidden="true">↗</span></a>
    </div>
</div>
