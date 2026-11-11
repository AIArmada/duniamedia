<div>
    <x-page-hero
        eyebrow="Media advisor · guided brief"
        title="Start with your goal.<br><span class='editorial'>We'll find the way.</span>"
        lede="A guided starting point for better media decisions — no media jargon required. Your answers create an illustrative direction, not a confirmed quotation."
    />

    <section class="form-shell">
        <div class="container">
            <div class="advisor-layout">
                <div>
                    <div class="form-panel advisor-form">
                        <span class="eyebrow">Your campaign</span>
                        <h2>Tell us what you're trying to achieve.</h2>
                        <p class="form-panel__intro">Six quick answers. We will use them to suggest a channel mix, creative considerations and questions to verify with real inventory.</p>

                        <div class="form-grid">
                            <label class="field">
                                <span>Main objective</span>
                                <select wire:model="objective">
                                    <option value="awareness">Grow brand awareness</option>
                                    <option value="launch">Launch a new product</option>
                                    <option value="leads">Generate enquiries / leads</option>
                                    <option value="sales">Support sales</option>
                                </select>
                            </label>
                            <label class="field">
                                <span>Indicative total budget</span>
                                <select wire:model="budget">
                                    <option value="under-15000">Under RM15,000</option>
                                    <option value="15000-50000">RM15,000–RM50,000</option>
                                    <option value="50000-150000">RM50,000–RM150,000</option>
                                    <option value="above-150000">Above RM150,000</option>
                                </select>
                            </label>
                            <label class="field">
                                <span>Target region</span>
                                <select wire:model="location">
                                    <option>Klang Valley</option>
                                    <option>Nationwide</option>
                                    <option>Johor</option>
                                    <option>Penang</option>
                                    <option>Sabah & Sarawak</option>
                                    <option>Other</option>
                                </select>
                            </label>
                            <label class="field">
                                <span>Campaign timeline</span>
                                <select wire:model="timeline">
                                    <option>1–3 months</option>
                                    <option>Within 4 weeks</option>
                                    <option>3–6 months</option>
                                    <option>Ongoing</option>
                                </select>
                            </label>
                            <label class="field">
                                <span>Target audience</span>
                                <input wire:model="audience" placeholder="e.g. young families, B2B owners">
                            </label>
                            <label class="field">
                                <span>Industry</span>
                                <input wire:model="industry" placeholder="e.g. F&B, health, education">
                            </label>
                        </div>
                        @error('objective')<span class="form-error">{{ $message }}</span>@enderror

                        <button wire:click="plan" class="button button--primary" type="button" style="margin-top:0.5rem">
                            Generate strategy direction <span aria-hidden="true">↗</span>
                        </button>

                        @if ($showResult)
                            <div class="advisor-result" aria-live="polite">
                                <span class="eyebrow">Illustrative direction · not a quotation</span>
                                <h2>Three directions worth comparing.</h2>
                                <p class="muted">For <strong>{{ ucfirst($objective) }}</strong> in <strong>{{ $location }}</strong>, use these as a starting point. Verify inventory, audience data and rates before committing.</p>

                                <div class="advisor-options">
                                    <div class="advisor-option">
                                        <span>01</span>
                                        <div><h3>Focused local reach</h3><p>Targeted digital and niche creators, paired with a conversion landing page and clear follow-up.</p></div>
                                    </div>
                                    <div class="advisor-option">
                                        <span>02</span>
                                        <div><h3>Balanced media mix</h3><p>Combine selected outdoor or audio with creator content and digital retargeting for repeated contact.</p></div>
                                    </div>
                                    <div class="advisor-option">
                                        <span>03</span>
                                        <div><h3>Broad brand visibility</h3><p>Evaluate broadcast, high-impact outdoor and coordinated creative when the budget supports sustained reach.</p></div>
                                    </div>
                                </div>

                                <p class="tiny muted">These are rule-based example directions, not live AI inference, measured forecasts or available quotations.</p>

                                @if (! $submitted)
                                    <div class="form-panel form-panel--plain" style="margin-top:1.5rem;padding:0;border:0">
                                        <h2 style="margin-top:1.5rem">Get a tailored recommendation.</h2>
                                        <div class="form-grid" style="margin-top:0.8rem">
                                            <label class="field">
                                                <span>Your name</span>
                                                <input wire:model="name" autocomplete="name" placeholder="Full name">
                                                @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                            </label>
                                            <label class="field">
                                                <span>Work email</span>
                                                <input wire:model="email" type="email" autocomplete="email" inputmode="email" placeholder="you@company.com">
                                                @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                            </label>
                                            <label class="field field--full">
                                                <span>Company <span class="form-hint">(optional)</span></span>
                                                <input wire:model="company" autocomplete="organization" placeholder="Organisation">
                                            </label>
                                        </div>
                                        <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1" autocomplete="off"></label></div>
                                        <label class="check">
                                            <input type="checkbox" wire:model="consent">
                                            <span>I agree to be contacted about this request and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                                        </label>
                                        @error('consent')<small class="form-error">{{ $message }}</small>@enderror
                                        <button wire:click="save" class="button button--primary" type="button" style="margin-top:0.8rem">Send my campaign brief <span aria-hidden="true">↗</span></button>
                                    </div>
                                @else
                                    <div class="success-panel" style="margin-top:1.5rem">
                                        <h2>Thank you.</h2>
                                        <p>Your campaign brief has been submitted for review. Our team can now prepare a tailored response.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <aside class="advisor-summary" aria-label="Your brief summary">
                    <figure class="quote-summary__media">
                        <img src="{{ \App\Support\MediaImage::url('assets/img/v2/service-planning.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/service-planning.webp', [480, 960]) }}" sizes="320px" alt="A media planning session with printed schedules and a city map" loading="lazy" decoding="async">
                    </figure>
                    @php
                        $budgetLabels = [
                            'under-15000' => 'Under RM15,000',
                            '15000-50000' => 'RM15,000–RM50,000',
                            '50000-150000' => 'RM50,000–RM150,000',
                            'above-150000' => 'Above RM150,000',
                        ];
                        $objectiveLabels = [
                            'awareness' => 'Grow brand awareness',
                            'launch' => 'Launch a new product',
                            'leads' => 'Generate enquiries / leads',
                            'sales' => 'Support sales',
                        ];
                    @endphp
                    <span class="eyebrow eyebrow--plain">Live summary</span>
                    <h2>Your brief so far</h2>
                    <dl>
                        <div><dt>Objective</dt><dd>{{ $objectiveLabels[$objective] ?? ucfirst($objective) }}</dd></div>
                        <div><dt>Budget</dt><dd>{{ $budgetLabels[$budget] ?? $budget }}</dd></div>
                        <div><dt>Region</dt><dd>{{ $location }}</dd></div>
                        <div><dt>Timeline</dt><dd>{{ $timeline }}</dd></div>
                        <div><dt>Audience</dt><dd>{{ $audience ?: 'To define' }}</dd></div>
                        <div><dt>Industry</dt><dd>{{ $industry ?: 'To define' }}</dd></div>
                    </dl>
                    <p>Editable at any time. This is a planning aid, not an automatically generated media buy.</p>
                    <a class="button button--secondary button--block" href="{{ route('quote.builder') }}" style="margin-top:0.9rem">Already know your media? Build a proposal</a>
                </aside>
            </div>
        </div>
    </section>
</div>
